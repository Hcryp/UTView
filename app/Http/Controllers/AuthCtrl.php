<?php namespace App\Http\Controllers;
use Illuminate\Http\Request; use Illuminate\Support\Facades\Auth;

class AuthCtrl extends Controller {
    public function login() { return view('auth.login'); }

    public function auth(Request $r) {
        $cred = $r->validate(['username' => 'required', 'password' => 'required']);
        if (Auth::attempt($cred)) {
            $r->session()->regenerate();
            return redirect()->intended('k3');
        }
        return back()->withErrors(['username' => 'Invalid credentials.']);
    }

    public function logout(Request $r) {
        Auth::logout(); $r->session()->invalidate(); $r->session()->regenerateToken();
        return redirect('/');
    }
}