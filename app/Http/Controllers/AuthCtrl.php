<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthCtrl extends Controller {
    // Show login form
    public function form() { return view('auth.login'); }

    // Process login attempt
    public function chk(Request $r) {
        $creds = $r->validate(['email' => 'required|email', 'password' => 'required']);
        if (Auth::attempt($creds)) { $r->session()->regenerate(); return redirect()->route('adm.dash'); }
        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    // Logout
    public function out(Request $r) {
        Auth::logout(); $r->session()->invalidate();
        return redirect()->route('wiki.idx');
    }
}