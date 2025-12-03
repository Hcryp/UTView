<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Manhour;

class AdmCtrl extends Controller
{
    function login() { return view('adm.login'); }

    function signin(Request $req) {
        $creds = $req->validate(['username' => 'required', 'password' => 'required']);
        if (Auth::attempt($creds)) {
            $req->session()->regenerate();
            return redirect()->intended('adm');
        }
        return back()->withErrors(['username' => 'Access Denied']);
    }
function dash() {
        // 1. Calculate Grand Totals
        $stats = [
            'mp' => Manhour::where('is_active', 1)->count(),
            'mh' => Manhour::where('is_active', 1)->sum('manhours'),
            'companies' => Manhour::distinct('company')->count(),
        ];

        // 2. Generate Summary
        $summary = Manhour::select('company', 'category')
            ->selectRaw('count(*) as manpower, sum(manhours) as total_hours')
            ->where('is_active', 1)
            ->groupBy('company', 'category')
            ->orderBy('total_hours', 'desc')
            ->get();

        // 3. Get Recent Detailed Rows
        $details = Manhour::where('is_active', 1)->latest()->limit(50)->get();

        // FIX: Extract user first, then use compact for everything
        $user = Auth::user();
        
        return view('adm.dash', compact('stats', 'summary', 'details', 'user'));
    }

    function out(Request $req) {
        Auth::logout();
        $req->session()->invalidate();
        return redirect('/login');
    }
}