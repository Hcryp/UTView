<?php namespace App\Http\Controllers;

use App\Models\Manpower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class ManpowerCtrl extends Controller {
    
    private function normalizeNrp($val) {
        $normalized = strtolower(trim($val));
        $placeholders = ['-', 'no id', 'n/a', 'none', 'unknown', 'no_id', 'no-id'];
        
        if (empty($val) || in_array($normalized, $placeholders)) {
            return null;
        }
        return trim($val);
    }

    private function applyAutoStatus(Request $r) {
        $r->merge(['nrp' => $this->normalizeNrp($r->nrp)]);
        if ($r->filled('end_date')) {
            $endDate = Carbon::parse($r->end_date);
            if ($endDate->isPast() && !$endDate->isToday()) {
                $r->merge(['status' => 'INACTIVE']);
                if (!$r->filled('date_out')) {
                    $r->merge(['date_out' => $r->end_date]);
                }
            }
        }
    }

    public function index(Request $request) {
        $query = Manpower::query();

        // 1. Search Filter
        $query->when($request->search, fn($q) => 
            $q->where(function($sub) use ($request) {
                $sub->where('name', 'like', "%{$request->search}%")
                    ->orWhere('nrp', 'like', "%{$request->search}%")
                    ->orWhere('role', 'like', "%{$request->search}%")
                    ->orWhere('company', 'like', "%{$request->search}%");
            })
        );

        // 2. Status Filter
        $status = $request->input('status', 'ACTIVE');
        if ($status === 'ACTIVE') {
            $query->where('status', 'ACTIVE');
        } elseif ($status === 'ALL') {
            // No status filter
        } else {
            $query->where('status', 'INACTIVE')->when($status !== 'INACTIVE', function($q) use ($status) {
                $q->where('out_reason', $status);
            });
        }

        // 3. Other Filters
        $query->when($request->category, fn($q) => $q->where('category', $request->category));
        $query->when($request->department, fn($q) => $q->where('department', $request->department));
        $query->when($request->site, fn($q) => $q->where('site', $request->site));

        // Clone for stats before sorting/paginating
        $summaryQuery = clone $query;

        // 4. Sorting Logic
        $sort = $request->input('sort', 'name'); // Default sort by Name
        $dir = $request->input('dir', 'asc');     // Default direction ASC

        switch ($sort) {
            case 'contract':
                // Special sort for Contract Date (End Date)
                // ASC: Expired/Earliest dates first, NULL (Permanent) last
                // DESC: NULL (Permanent) first, Latest dates next
                if ($dir === 'asc') {
                    $query->orderByRaw('CASE WHEN end_date IS NULL THEN 1 ELSE 0 END, end_date ASC');
                } else {
                    $query->orderByRaw('CASE WHEN end_date IS NULL THEN 0 ELSE 1 END, end_date DESC');
                }
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'company':
                $query->orderBy('company', $dir)->orderBy('site', $dir);
                break;
            case 'role':
                $query->orderBy('role', $dir);
                break;
            case 'manhours':
                $query->orderBy('manhours', $dir);
                break;
            case 'status':
                $query->orderBy('status', $dir)->orderBy('out_reason', $dir);
                break;
            default: // 'name'
                $query->orderBy('name', $dir);
                break;
        }

        // 5. Statistics
        $detailedTable = $summaryQuery->clone()
            ->select('category', 'company', DB::raw('count(*) as mp'), DB::raw('sum(manhours) as mh'))
            ->groupBy('category', 'company')
            ->orderBy('category', 'desc')->orderBy('company')->get();
        
        $highLevelTable = $summaryQuery->clone()
            ->select('category', DB::raw('count(*) as mp'), DB::raw('sum(manhours) as mh'))
            ->groupBy('category')->orderBy('category', 'desc')->get();

        $summary = [
            'total_mp' => $query->count(),
            'total_mh' => $query->sum('manhours')
        ];

        // 6. Pagination
        $data = $query->paginate($request->input('per_page', 20))->withQueryString();
        
        $sites = Manpower::distinct()->whereNotNull('site')->orderBy('site')->pluck('site');
        $categories = Manpower::distinct()->whereNotNull('category')->orderBy('category')->pluck('category');
        $departments = Manpower::distinct()->whereNotNull('department')->orderBy('department')->pluck('department');
        $out_reasons = Manpower::distinct()->whereNotNull('out_reason')->orderBy('out_reason')->pluck('out_reason');

        if ($request->ajax()) {
            return view('dash.manpower', compact('data', 'summary', 'detailedTable', 'highLevelTable', 'sites', 'categories', 'departments', 'out_reasons'))->fragment('manpower-table');
        }

        return view('dash.manpower', compact('data', 'summary', 'detailedTable', 'highLevelTable', 'sites', 'categories', 'departments', 'out_reasons'));
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
        $existing_companies = Manpower::distinct()->whereNotNull('company')->orderBy('company')->pluck('company');
        $existing_roles = Manpower::distinct()->whereNotNull('role')->orderBy('role')->pluck('role');
        return view('dash.manpower-form', [
            'mp' => new Manpower(), 
            'title' => 'New Manpower Entry', 'mode' => 'create',
            'opt_categories' => Manpower::CATEGORIES, 'opt_depts' => Manpower::DEPARTMENTS,
            'opt_sites' => ['SATUI', 'BATULICIN'], 'opt_out_reasons' => Manpower::OUT_REASONS,
            'existing_companies' => $existing_companies, 'existing_roles' => $existing_roles
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
        return redirect()->route('manpower.show', $mp->id)->with('success', 'Manpower record created successfully.');
    }

    public function show(Manpower $manpower) {
        return view('dash.manpower-show', ['mp' => $manpower]);
    }

    public function edit(Manpower $manpower) {
        $existing_companies = Manpower::distinct()->whereNotNull('company')->orderBy('company')->pluck('company');
        $existing_roles = Manpower::distinct()->whereNotNull('role')->orderBy('role')->pluck('role');
        return view('dash.manpower-form', [
            'mp' => $manpower, 'title' => 'Edit Manpower', 'mode' => 'edit',
            'opt_categories' => Manpower::CATEGORIES, 'opt_depts' => Manpower::DEPARTMENTS,
            'opt_sites' => ['SATUI', 'BATULICIN'], 'opt_out_reasons' => Manpower::OUT_REASONS,
            'existing_companies' => $existing_companies, 'existing_roles' => $existing_roles
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
        return redirect()->route('manpower.index')->with('success', 'Record deleted successfully.');
    }
}