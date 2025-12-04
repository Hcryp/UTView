<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WikiCtrl;
use App\Http\Controllers\K3Ctrl;
use App\Http\Controllers\AuthCtrl;

// Public Wiki
Route::get('/', [WikiCtrl::class, 'index'])->name('wiki.index');
Route::get('/topic/{slug}', [WikiCtrl::class, 'show'])->name('wiki.show');

// Auth
Route::get('/login', [AuthCtrl::class, 'login'])->name('login');
Route::post('/login', [AuthCtrl::class, 'auth'])->name('auth.check');
Route::post('/logout', [AuthCtrl::class, 'logout'])->name('logout');

// Protected Analytic Dashboard
Route::middleware('auth')->prefix('k3')->group(function(){
    Route::get('/', [K3Ctrl::class, 'index'])->name('k3.index');
});