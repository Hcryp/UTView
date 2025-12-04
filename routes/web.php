<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WikiCtrl;
use App\Http\Controllers\DataCtrl; // Replaced K3Ctrl with DataCtrl
use App\Http\Controllers\AuthCtrl;

// Public
Route::get('/', [WikiCtrl::class, 'index'])->name('wiki.index');
Route::get('/topic/{slug}', [WikiCtrl::class, 'show'])->name('wiki.show');

// Auth
Route::get('/login', [AuthCtrl::class, 'login'])->name('login');
Route::post('/login', [AuthCtrl::class, 'auth'])->name('auth.check');
Route::post('/logout', [AuthCtrl::class, 'logout'])->name('logout');

// Admin Dashboard (Protected)
Route::middleware('auth')->prefix('dash')->group(function(){
    // Overview
    Route::get('/', [DataCtrl::class, 'index'])->name('dash.index');
    
    // Wiki Manager
    Route::get('/wiki', [WikiCtrl::class, 'manage'])->name('dash.wiki');
    
    // Data Manager
    Route::get('/data', [DataCtrl::class, 'manage'])->name('dash.data');
});