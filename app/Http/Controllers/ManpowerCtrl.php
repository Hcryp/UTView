<?php namespace App\Http\Controllers;

use App\Models\Manpower;
use App\Models\ManpowerLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class ManpowerCtrl extends Controller {
    
    private function normalizeNrp($val) {
        $normalized = strtolower(trim($val));
        $placeholders = ['-', 'no id', 'n/a', 'none', 'unknown', 'no_id', 'no-id'];
        return (empty($val) || in_array($normalized, $placeholders)) ? null : trim($val);
    }

    private function applyAutoStatus(Request $r) {
        $r->merge(['nrp' => $this->normalizeNrp($r->nrp)]);
        if ($r->filled('end_date')) {
            $endDate = Carbon::parse($r->end_date);
            if ($endDate->isPast() && !$endDate->isToday()) {
                $r->merge(['status' => 'INACTIVE']);
                if (!$r->filled('date_out')) $r->merge(['date_out' => $r->end_date]);
            }
        }
    }

    private function parseCsv($path) {
        if (!file_exists($path)) return [];
        
        $handle = fopen($path, 'r');
        if (!$handle) return [];

        // Read first line to guess delimiter
        $firstLine = fgets($handle);
        rewind($handle);
        
        $delimiter = (strpos($firstLine, ';') !== false) ? ';' : ',';
        
        $data = [];
        while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
            // Remove BOM if present in first cell
            if (isset($row[0])) {
                $row[0] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $row[0]);
            }
            $data[] = $row;
        }
        fclose($handle);
        
        return $data;
    }

    public function index(Request $request) {
        $query = Manpower::query();

        $query->when($request->search, fn($q) => 
            $q->where(function($sub) use ($request) {
                $sub->where('name', 'like', "%{$request->search}%")
                    ->orWhere('nrp', 'like', "%{$request->search}%")
                    ->orWhere('role', 'like', "%{$request->search}%")
                    ->orWhere('company', 'like', "%{$request->search}%");
            })
        );

        $status = $request->input('status', 'ACTIVE');
        if ($status === 'ACTIVE') {
            $query->where('status', 'ACTIVE');
        } elseif ($status !== 'ALL') {
            $query->where('status', 'INACTIVE')->when($status !== 'INACTIVE', fn($q) => $q->where('out_reason', $status));
        }

        $query->when($request->category, fn($q) => $q->where('category', $request->category));
        $query->when($request->department, fn($q) => $q->where('department', $request->department));
        $query->when($request->site, fn($q) => $q->where('site', $request->site));

        $summaryQuery = clone $query;
        $sort = $request->input('sort', 'name');
        $dir = $request->input('dir', 'asc');

        switch ($sort) {
            case 'contract': $query->orderByRaw('CASE WHEN end_date IS NULL THEN '.($dir === 'asc' ? '1' : '0').' ELSE '.($dir === 'asc' ? '0' : '1').' END, end_date '.$dir); break;
            case 'newest': $query->orderBy('created_at', 'desc'); break;
            case 'company': $query->orderBy('company', $dir)->orderBy('site', $dir); break;
            case 'role': $query->orderBy('role', $dir); break;
            case 'manhours': $query->orderBy('manhours', $dir); break;
            case 'status': $query->orderBy('status', $dir)->orderBy('out_reason', $dir); break;
            default: $query->orderBy('name', $dir); break;
        }

        $detailedTable = $summaryQuery->clone()
            ->select('category', 'company', DB::raw('count(*) as mp'), DB::raw('sum(manhours) as mh'))
            ->groupBy('category', 'company')
            ->orderBy('category', 'desc')->orderBy('company')->get();
        
        $highLevelTable = $summaryQuery->clone()
            ->select('category', DB::raw('count(*) as mp'), DB::raw('sum(manhours) as mh'))
            ->groupBy('category')->orderBy('category', 'desc')->get();

        $summary = ['total_mp' => $query->count(), 'total_mh' => $query->sum('manhours')];
        $data = $query->paginate($request->input('per_page', 20))->withQueryString();
        
        $sites = Manpower::distinct()->whereNotNull('site')->orderBy('site')->pluck('site');
        $categories = Manpower::distinct()->whereNotNull('category')->orderBy('category')->pluck('category');
        $departments = Manpower::distinct()->whereNotNull('department')->orderBy('department')->pluck('department');
        $out_reasons = Manpower::distinct()->whereNotNull('out_reason')->orderBy('out_reason')->pluck('out_reason');
        
        $logs = ManpowerLog::orderBy('log_date', 'desc')->limit(5)->get();

        if ($request->ajax()) {
            return view('dash.manpower', compact('data', 'summary', 'detailedTable', 'highLevelTable', 'sites', 'categories', 'departments', 'out_reasons', 'logs'))->fragment('manpower-table');
        }

        return view('dash.manpower', compact('data', 'summary', 'detailedTable', 'highLevelTable', 'sites', 'categories', 'departments', 'out_reasons', 'logs'));
    }

    public function monthlyRecap(Request $request) {
        $month = $request->input('month', Carbon::now()->format('Y-m'));
        $start = Carbon::parse($month)->startOfMonth();
        $end = Carbon::parse($month)->endOfMonth();
        
        $logs = ManpowerLog::whereBetween('log_date', [$start->format('Y-m-d'), $end->format('Y-m-d')])->orderBy('log_date')->get();
        $activeMatrix = [];
        
        foreach ($logs as $log) {
            foreach ($log->content as $entry) {
                $entry = (object)$entry;
                $id = $entry->id;
                
                if (!isset($activeMatrix[$id])) {
                    $activeMatrix[$id] = [
                        'name' => $entry->name,
                        'nrp' => $entry->nrp,
                        'company' => $entry->company,
                        'role' => $entry->role,
                        'category' => $entry->category,
                        'site' => $entry->site,
                        'total_mh' => 0
                    ];
                }
                $activeMatrix[$id]['total_mh'] += isset($entry->manhours) ? (float)$entry->manhours : 0;
            }
        }
        usort($activeMatrix, fn($a, $b) => strcmp($a['name'], $b['name']));

        $inactiveData = Manpower::whereBetween('date_out', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                                ->orderBy('date_out')
                                ->get();

        $totals = [
            'active_mp' => count($activeMatrix),
            'total_mh' => array_sum(array_column($activeMatrix, 'total_mh')),
            'out_mp' => $inactiveData->count()
        ];

        return view('dash.manpower-monthly', compact('activeMatrix', 'inactiveData', 'month', 'totals'));
    }

    public function import(Request $request) {
        $month = $request->input('month', Carbon::now()->format('Y-m'));
        return view('dash.manpower-import', compact('month'));
    }

    public function previewImport(Request $request) {
        $request->validate([
            'file' => 'nullable|file',
            'csv_content' => 'nullable|string'
        ]);
        
        $data = [];

        if ($request->filled('csv_content')) {
            $tempPath = tempnam(sys_get_temp_dir(), 'imp_');
            file_put_contents($tempPath, $request->input('csv_content'));
            $data = $this->parseCsv($tempPath);
            unlink($tempPath);
        }
        elseif ($request->hasFile('file')) {
            $file = $request->file('file');
            $data = $this->parseCsv($file->getRealPath());
        } 
        else {
            return back()->with('error', 'No file data received.');
        }

        if (empty($data)) {
            return back()->with('error', 'The file appears to be empty or unreadable.');
        }

        $header = array_map(function($h) {
            return trim(strtoupper($h));
        }, array_shift($data)); 
        
        $previewData = [];
        $type = 'unknown';

        if (in_array('TANGGAL OUT', $header)) $type = 'out';
        elseif (in_array('MANHOURS', $header)) $type = 'in'; // Kept for detection, but value will be recalculated
        else {
            return back()->with('error', 'Invalid Format. Required columns "MANHOURS" or "TANGGAL OUT" not found.');
        }

        foreach ($data as $i => $row) {
            if (count($row) !== count($header)) continue;
            if ($i > 50) break; 

            $row = array_combine($header, $row);
            $nrp = $this->normalizeNrp($row['NRP'] ?? '');
            
            if ($type === 'out') {
                $previewData[] = [
                    'nrp' => $nrp,
                    'name' => $row['NAMA'] ?? 'Unknown',
                    'company' => $row['PERUSAHAAN'] ?? '',
                    'date_out' => $row['TANGGAL OUT'] ?? null,
                    'out_reason' => $row['KETERANGAN PEKERJA OUT'] ?? 'RESIGN',
                    'action' => 'Update Status'
                ];
            } else {
                // Calculation Logic: Effective Days * 12
                $days = isset($row['HARI KERJA EFEKTIF PERBULAN']) ? (int)$row['HARI KERJA EFEKTIF PERBULAN'] : 0;
                
                // Fallback: If days is 0 or invalid, try to use Manhours but sanitize it heavily
                // However, user specifically asked to correct based on logic 24 -> 288.
                // If days > 0, we calculate. If days is missing but manhours exists, we check if it's crazy high.
                
                if ($days > 0) {
                    $manhours = $days * 12;
                } else {
                    // Try to parse manhours, if > 744 (31*24), it's likely garbage, default to 0
                    $rawMh = isset($row['MANHOURS']) ? (float)str_replace(',', '', $row['MANHOURS']) : 0;
                    $manhours = ($rawMh > 744) ? 0 : $rawMh; 
                }

                $previewData[] = [
                    'nrp' => $nrp,
                    'name' => $row['NAMA'] ?? 'Unknown',
                    'company' => $row['PERUSAHAAN'] ?? '',
                    'effective_days' => $days,
                    'manhours' => $manhours,
                    'site' => $row['CABANG/SITE'] ?? 'SATUI', // Ensure these are captured
                    'category' => $row['KATEGORI PEKERJA'] ?? 'KARYAWAN',
                    'department' => $row['DEPARTEMEN'] ?? null,
                    'role' => $row['JABATAN'] ?? null,
                    'action' => 'Update/Create'
                ];
            }
        }

        return view('dash.manpower-import', [
            'month' => $request->input('month'),
            'previewData' => $previewData,
            'importType' => $type
        ]);
    }

    public function processImport(Request $request) {
        $data = json_decode($request->input('confirmed_data'), true);
        $count = 0;

        if ($data) {
            foreach ($data as $row) {
                $type = isset($row['date_out']) ? 'out' : 'in';
                $nrp = $row['nrp'];

                if ($type === 'out') {
                    if (!$nrp) continue;
                    $mp = Manpower::where('nrp', $nrp)->first();
                    if ($mp) {
                        try {
                            $dateOut = $row['date_out'] ? Carbon::parse($row['date_out']) : null;
                        } catch (\Exception $e) {
                            $dateOut = null; 
                        }

                        $mp->update([
                            'status' => 'INACTIVE',
                            'date_out' => $dateOut,
                            'out_reason' => $row['out_reason'] ?? 'RESIGN'
                        ]);
                        $count++;
                    }
                } else {
                    // Re-Sanitize Manhours to be safe (Effective Days * 12)
                    $days = isset($row['effective_days']) ? (int)$row['effective_days'] : 0;
                    $manhours = isset($row['manhours']) ? (float)$row['manhours'] : 0;
                    
                    if ($days > 0 && $manhours > 744) {
                        $manhours = $days * 12; // Force recalculate if raw data slipped through
                    } elseif ($manhours > 744) {
                        $manhours = 0; // Prevent crash on huge numbers
                    }

                    $payload = [
                        'name' => $row['name'],
                        'company' => $row['company'],
                        'effective_days' => $days,
                        'manhours' => $manhours, 
                        'status' => 'ACTIVE',
                        // Ensure default values for required fields that might be missing in older records
                        'site' => $row['site'] ?? 'SATUI', 
                        'category' => $row['category'] ?? 'KARYAWAN',
                        'department' => $row['department'] ?? null,
                        'role' => $row['role'] ?? null
                    ];

                    if ($nrp) {
                        Manpower::updateOrCreate(['nrp' => $nrp], $payload);
                    } else {
                        Manpower::updateOrCreate(['name' => $payload['name'], 'company' => $payload['company']], $payload);
                    }
                    $count++;
                }
            }
        }

        return redirect()->route('manpower.recap', ['month' => $request->input('month')])
                         ->with('success', "Import processed. $count records updated.");
    }

    public function export(Request $request) {
        $month = $request->input('month');
        
        $headers = [
            'Content-type' => 'text/csv', 
            'Content-Disposition' => 'attachment; filename=manpower_export_'.($month ?? 'master').'.csv',
            'Pragma' => 'no-cache', 
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0', 
            'Expires' => '0'
        ];
        
        $callback = function() use ($month) {
            $file = fopen('php://output', 'w');
            
            if ($month) {
                // Export Monthly Recap Data
                fputcsv($file, ['Month: ' . $month]);
                fputcsv($file, []);
                
                // Section 1: Active & Manhours
                fputcsv($file, ['SECTION: ACTIVE MANPOWER & ACCUMULATED HOURS']);
                fputcsv($file, ['Name', 'NRP', 'Company', 'Role', 'Category', 'Site', 'Accumulated Hours (Month)']);
                
                $start = Carbon::parse($month)->startOfMonth();
                $end = Carbon::parse($month)->endOfMonth();
                $logs = ManpowerLog::whereBetween('log_date', [$start, $end])->get();
                $active = [];
                
                foreach ($logs as $log) {
                    foreach ($log->content as $entry) {
                        $entry = (object)$entry;
                        $id = $entry->id;
                        if (!isset($active[$id])) {
                            $active[$id] = $entry;
                            $active[$id]->total = 0;
                        }
                        $active[$id]->total += isset($entry->manhours) ? (float)$entry->manhours : 0;
                    }
                }
                
                foreach ($active as $row) {
                    fputcsv($file, [$row->name, $row->nrp, $row->company, $row->role, $row->category, $row->site, $row->total]);
                }

                fputcsv($file, []);
                
                // Section 2: Out
                fputcsv($file, ['SECTION: INACTIVE / OUT']);
                fputcsv($file, ['Name', 'NRP', 'Company', 'Date Out', 'Reason']);
                $outs = Manpower::whereBetween('date_out', [$start, $end])->get();
                foreach ($outs as $out) {
                    fputcsv($file, [$out->name, $out->nrp, $out->company, $out->date_out, $out->out_reason]);
                }

            } else {
                // Export Master Data
                $columns = ['ID', 'Site', 'Category', 'Company', 'NRP', 'Name', 'Department', 'Role', 'Join Date', 'End Date', 'Effective Days', 'Manhours', 'Status', 'Date Out', 'Out Reason'];
                fputcsv($file, $columns);
                Manpower::chunk(500, function($rows) use ($file) {
                    foreach ($rows as $row) {
                        fputcsv($file, [
                            $row->id, $row->site, $row->category, $row->company, $row->nrp, $row->name, 
                            $row->department, $row->role, $row->join_date, $row->end_date, 
                            $row->effective_days, $row->manhours, $row->status, $row->date_out, $row->out_reason
                        ]);
                    }
                });
            }
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    public function logDaily() {
        $today = Carbon::today()->format('Y-m-d');
        $currentRecords = Manpower::whereDate('updated_at', $today)->get();
        if ($currentRecords->isEmpty()) return back()->with('error', 'No modified data found for today (' . $today . ').');

        $lastLog = ManpowerLog::where('log_date', '<', $today)->orderBy('log_date', 'desc')->first();
        $previousData = [];
        if ($lastLog && !empty($lastLog->content)) {
            foreach ($lastLog->content as $item) {
                $item = (array)$item;
                $previousData[$item['id']] = $item['cumulative_mh'] ?? 0;
            }
        }

        $logContent = [];
        $totalDailyMh = 0;

        foreach ($currentRecords as $record) {
            $prevMh = $previousData[$record->id] ?? 0;
            $currentMh = $record->manhours;
            $dailyMh = $currentMh - $prevMh;

            $logContent[] = [
                'id' => $record->id, 'name' => $record->name, 'nrp' => $record->nrp,
                'company' => $record->company, 'site' => $record->site, 'role' => $record->role,
                'department' => $record->department, 'category' => $record->category,
                'end_date' => $record->end_date, 'status' => $record->status, 'out_reason' => $record->out_reason,
                'cumulative_mh' => $currentMh, 'previous_mh' => $prevMh, 'manhours' => $dailyMh,
            ];
            $totalDailyMh += $dailyMh;
        }

        ManpowerLog::updateOrCreate(
            ['log_date' => $today],
            ['content' => $logContent, 'total_mp' => count($logContent), 'total_mh' => $totalDailyMh]
        );

        return back()->with('success', 'Daily recap for '.$today.' calculated. Total Daily Hours: ' . number_format($totalDailyMh, 2));
    }

    public function showLog($id) {
        $log = ManpowerLog::findOrFail($id);
        return view('dash.manpower-log-show', compact('log'));
    }

    public function editLog($id) {
        $log = ManpowerLog::findOrFail($id);
        return view('dash.manpower-log-edit', compact('log'));
    }

    public function updateLog(Request $r, $id) {
        $log = ManpowerLog::findOrFail($id);
        $val = $r->validate([
            'items' => 'required|array', 'items.*.name' => 'required|string',
            'items.*.manhours' => 'required|numeric', 'items.*.cumulative_mh' => 'nullable|numeric',
            'items.*.site' => 'required|string', 'items.*.company' => 'required|string', 'items.*.category' => 'required|string',
        ]);
        $items = array_values($val['items']);
        $log->update(['content' => $items, 'total_mp' => count($items), 'total_mh' => collect($items)->sum('manhours')]);
        return redirect()->route('manpower.log.show', $id)->with('success', 'Log audit saved. Totals recalculated.');
    }

    public function checkNrp(Request $request) {
        $nrp = $request->query('nrp');
        $cleanNrp = $this->normalizeNrp($nrp);
        if (!$cleanNrp) return response()->json(['exists' => false]);
        $query = Manpower::where('nrp', $cleanNrp);
        if ($request->query('ignore_id')) $query->where('id', '!=', $request->query('ignore_id'));
        return response()->json(['exists' => $query->exists()]);
    }

    public function create() {
        return view('dash.manpower-form', [
            'mp' => new Manpower(), 'title' => 'New Manpower Entry', 'mode' => 'create',
            'opt_categories' => Manpower::CATEGORIES, 'opt_depts' => Manpower::DEPARTMENTS,
            'opt_sites' => ['SATUI', 'BATULICIN'], 'opt_out_reasons' => Manpower::OUT_REASONS,
            'existing_companies' => Manpower::distinct()->whereNotNull('company')->orderBy('company')->pluck('company'),
            'existing_roles' => Manpower::distinct()->whereNotNull('role')->orderBy('role')->pluck('role')
        ]);
    }

    public function store(Request $r) {
        $this->applyAutoStatus($r);
        $val = $r->validate([
            'site' => 'required', 'company' => 'required', 'category' => 'required', 'name' => 'required',
            'nrp' => 'nullable|unique:manpowers,nrp', 'department' => 'nullable', 'role' => 'nullable',
            'join_date' => 'nullable|date', 'end_date' => 'nullable|date',
            'effective_days' => 'integer|min:0', 'manhours' => 'numeric|min:0', 'status' => 'required',
            'date_out' => 'nullable|date|required_if:status,INACTIVE', 'out_reason' => 'nullable|required_if:status,INACTIVE'
        ]);
        $mp = Manpower::create($val);
        return redirect()->route('manpower.show', $mp->id)->with('success', 'Manpower record created.');
    }

    public function show(Manpower $manpower) {
        $recentLogs = ManpowerLog::orderBy('log_date', 'desc')->take(30)->get();
        $history = [];
        foreach ($recentLogs as $log) {
            $entry = collect($log->content)->firstWhere('id', $manpower->id);
            if ($entry) {
                $entry = (object)$entry;
                $history[] = ['date' => $log->log_date, 'manhours' => $entry->manhours ?? 0, 'cumulative' => $entry->cumulative_mh ?? 0, 'role' => $entry->role ?? '-'];
            }
        }
        return view('dash.manpower-show', ['mp' => $manpower, 'history' => $history]);
    }

    public function edit(Manpower $manpower) {
        return view('dash.manpower-form', [
            'mp' => $manpower, 'title' => 'Edit Manpower', 'mode' => 'edit',
            'opt_categories' => Manpower::CATEGORIES, 'opt_depts' => Manpower::DEPARTMENTS,
            'opt_sites' => ['SATUI', 'BATULICIN'], 'opt_out_reasons' => Manpower::OUT_REASONS,
            'existing_companies' => Manpower::distinct()->whereNotNull('company')->orderBy('company')->pluck('company'),
            'existing_roles' => Manpower::distinct()->whereNotNull('role')->orderBy('role')->pluck('role')
        ]);
    }

    public function update(Request $r, Manpower $manpower) {
        $this->applyAutoStatus($r);
        $val = $r->validate([
            'site' => 'required', 'company' => 'required', 'category' => 'required', 'name' => 'required',
            'nrp' => ['nullable', Rule::unique('manpowers', 'nrp')->ignore($manpower->id)], 
            'department' => 'nullable', 'role' => 'nullable',
            'join_date' => 'nullable|date', 'end_date' => 'nullable|date',
            'effective_days' => 'integer|min:0', 'manhours' => 'numeric|min:0', 'status' => 'required', 
            'date_out' => 'nullable|date', 'out_reason' => 'nullable'
        ]);
        $manpower->update($val);
        return redirect()->route('manpower.show', $manpower->id)->with('success', 'Manpower details updated.');
    }

    public function destroy(Manpower $manpower) {
        $manpower->delete();
        return redirect()->route('manpower.index')->with('success', 'Record deleted.');
    }
}