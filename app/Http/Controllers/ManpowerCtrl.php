<?php namespace App\Http\Controllers;

use App\Models\Manpower;
use Illuminate\Http\Request;

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

        // 3. Category Filter
        $query->when($request->category, fn($q) => $q->where('category', $request->category));

        // 4. Site Filter
        $query->when($request->site, fn($q) => $q->where('site', $request->site));

        // 5. Summaries
        $summary = [
            'total_mp'  => $query->count(),
            'total_mh'  => $query->sum('manhours'),
            'companies' => $query->distinct('company')->count('company')
        ];

        // 6. Fetch Data (Fixed 20 per page)
        $data = $query->orderBy('name')->paginate(20)->withQueryString();

        // Dropdown Data
        $categories = Manpower::distinct()->pluck('category');
        $sites = Manpower::distinct()->pluck('site');

        // AJAX Return
        if ($request->ajax()) {
            return view('dash.manpower_table', compact('data'))->render();
        }

        return view('dash.manpower', compact('data', 'summary', 'categories', 'sites'));
    }
}