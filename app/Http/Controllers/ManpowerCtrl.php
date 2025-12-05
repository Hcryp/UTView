<?php namespace App\Http\Controllers;

use App\Models\Manpower;
use App\Models\ManpowerLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

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
            case 'contract':
                $query->orderByRaw('CASE WHEN end_date IS NULL THEN '.($dir === 'asc' ? '1' : '0').' ELSE '.($dir === 'asc' ? '0' : '1').' END, end_date '.$dir);
                break;
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

    public function logDaily() {
        $today = Carbon::today()->format('Y-m-d');
        
        // 1. Fetch records modified today (00:00 - 23:59)
        $currentRecords = Manpower::whereDate('updated_at', $today)->get();
        
        if ($currentRecords->isEmpty()) {
            return back()->with('error', 'No modified data found for today (' . $today . ').');
        }

        // 2. Fetch the closest previous log to calculate differences
        $lastLog = ManpowerLog::where('log_date', '<', $today)
                    ->orderBy('log_date', 'desc')
                    ->first();

        // 3. Build a map of previous cumulative manhours [id => cumulative_mh]
        $previousData = [];
        if ($lastLog && !empty($lastLog->content)) {
            foreach ($lastLog->content as $item) {
                // Ensure array format whether stored as object or array
                $item = (array)$item;
                $previousData[$item['id']] = $item['cumulative_mh'] ?? 0;
            }
        }

        $logContent = [];
        $totalDailyMh = 0;

        foreach ($currentRecords as $record) {
            // Logic: Daily Delta = Current Cumulative - Previous Cumulative
            $prevMh = $previousData[$record->id] ?? 0;
            $currentMh = $record->manhours; // This is cumulative in DB
            $dailyMh = $currentMh - $prevMh;

            // Optional: Prevent negative daily hours if data is inconsistent, 
            // strictly speaking delta shouldn't be negative unless correction.
            // keeping it raw allows admins to see issues.

            $logContent[] = [
                'id' => $record->id,
                'name' => $record->name,
                'nrp' => $record->nrp,
                'company' => $record->company,
                'site' => $record->site,
                'role' => $record->role,
                'department' => $record->department,
                'category' => $record->category,
                'end_date' => $record->end_date,
                'status' => $record->status,
                'out_reason' => $record->out_reason,
                
                // Analytics Data
                'cumulative_mh' => $currentMh,
                'previous_mh' => $prevMh,
                'manhours' => $dailyMh, // Store delta as the main 'manhours' for this log
            ];

            $totalDailyMh += $dailyMh;
        }

        // 4. Save Log
        ManpowerLog::updateOrCreate(
            ['log_date' => $today],
            [
                'content' => $logContent,
                'total_mp' => count($logContent),
                'total_mh' => $totalDailyMh
            ]
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
            'items' => 'required|array',
            'items.*.name' => 'required|string',
            'items.*.manhours' => 'required|numeric', // Daily Hours
            'items.*.cumulative_mh' => 'nullable|numeric',
            'items.*.site' => 'required|string',
            'items.*.company' => 'required|string',
            'items.*.category' => 'required|string',
        ]);

        $items = array_values($val['items']);
        
        // Auto-calculate new totals from the audited items
        $total_mp = count($items);
        $total_mh = collect($items)->sum('manhours'); // Summing daily deltas

        $log->update([
            'content' => $items,
            'total_mp' => $total_mp,
            'total_mh' => $total_mh
        ]);

        return redirect()->route('manpower.log.show', $id)->with('success', 'Log audit saved. Totals recalculated.');
    }

    public function checkNrp(Request $request) {
        $nrp = $request->query('nrp');
        $ignoreId = $request->query('ignore_id');
        $cleanNrp = $this->normalizeNrp($nrp);
        if (!$cleanNrp) return response()->json(['exists' => false]);
        $query = Manpower::where('nrp', $cleanNrp);
        if ($ignoreId) $query->where('id', '!=', $ignoreId);
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
        return view('dash.manpower-show', ['mp' => $manpower]);
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