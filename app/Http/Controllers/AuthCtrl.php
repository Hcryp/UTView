<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request; use Illuminate\Support\Facades\Auth;

class AuthCtrl extends Controller {
    public function form() { return view('auth.login'); }

    public function in(Request $r) {
        // Validate Username and Password
        $creds = $r->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        // Attempt login with username
        if (Auth::attempt($creds)) {
            $r->session()->regenerate();
            return redirect()->intended(route('adm.dash'));
        }

        return back()->withErrors(['password' => 'Invalid username or password.']);
    }

    public function out(Request $r) {
        Auth::logout(); $r->session()->invalidate(); $r->session()->regenerateToken();
        return redirect()->route('login');
    }
}