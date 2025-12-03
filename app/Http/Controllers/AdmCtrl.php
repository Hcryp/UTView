<?php

namespace App\Http\Controllers;
use App\Models\{ActMp, InActMp, Doc};
use Illuminate\Support\Facades\DB;

class AdmCtrl extends Controller {
    public function dash() {
        // 1. Key Metrics
        $stats = [
            'tot_mp'  => ActMp::count(),
            'tot_mh'  => ActMp::sum('manhours'),
            'out_mp'  => InActMp::count(),
            'docs'    => Doc::count()
        ];

        // 2. Summary Table: Group by Category & Company
        // Mimics "SUMMARY ManPower & ManHours" from your doc
        $report = ActMp::select(
                'worker_category', 
                'company', 
                DB::raw('count(*) as total_mp'), 
                DB::raw('sum(manhours) as total_mh')
            )
            ->groupBy('worker_category', 'company')
            ->orderBy('worker_category', 'desc')
            ->orderBy('total_mp', 'desc')
            ->get();

        return view('adm.dash', compact('stats', 'report'));
    }
}