<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WikiCtrl;
use App\Http\Controllers\DashCtrl; 
use App\Http\Controllers\AuthCtrl;

// Public & Auth
Route::get('/', [WikiCtrl::class, 'index'])->name('wiki.index');
Route::get('/topic/{slug}', [WikiCtrl::class, 'show'])->name('wiki.show');
Route::get('/login', [AuthCtrl::class, 'login'])->name('login');
Route::post('/login', [AuthCtrl::class, 'auth'])->name('auth.check');
Route::post('/logout', [AuthCtrl::class, 'logout'])->name('logout');

// Admin Dashboard
Route::middleware('auth')->prefix('dash')->group(function(){
    Route::get('/', [DashCtrl::class, 'index'])->name('dash.index');
    Route::get('/wiki', [DashCtrl::class, 'wikiMgr'])->name('dash.wiki');
    Route::get('/data', [DashCtrl::class, 'dataMgr'])->name('dash.data');
});