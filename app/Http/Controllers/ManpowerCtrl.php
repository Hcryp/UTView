<?php namespace App\Http\Controllers;

use App\Models\Manpower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManpowerCtrl extends Controller {
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
        if ($status !== 'ALL') {
            $query->where('status', $status);
        }

        // 3. Category & Site Filters
        $query->when($request->category, fn($q) => $q->where('category', $request->category));
        $query->when($request->site, fn($q) => $q->where('site', $request->site));

        // --- SUMMARIES ---
        $summaryQuery = clone $query;

        $detailedTable = $summaryQuery->clone()
            ->select('category', 'company', DB::raw('count(*) as mp'), DB::raw('sum(manhours) as mh'))
            ->groupBy('category', 'company')
            ->orderBy('category', 'desc')->orderBy('company')->get();

        $highLevelTable = $summaryQuery->clone()
            ->select('category', DB::raw('count(*) as mp'), DB::raw('sum(manhours) as mh'))
            ->groupBy('category')->orderBy('category', 'desc')->get();

        $summary = [
            'total_mp'  => $query->count(),
            'total_mh'  => $query->sum('manhours'),
        ];

        // --- MAIN DATA LIST ---
        // Default to 20 per page as requested
        $perPage = $request->input('per_page', 20); 
        $data = $query->orderBy('name')->paginate($perPage)->withQueryString();

        $categories = Manpower::distinct()->pluck('category');
        $sites = Manpower::distinct()->pluck('site');

        // *** SINGLE FILE AJAX HANDLING ***
        // This tells Laravel to only render the @fragment('manpower-table') section
        if ($request->ajax()) {
            return view('dash.manpower', compact('data', 'summary', 'detailedTable', 'highLevelTable', 'categories', 'sites'))
                ->fragment('manpower-table');
        }

        return view('dash.manpower', compact('data', 'summary', 'detailedTable', 'highLevelTable', 'categories', 'sites'));
    }
}