<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WikiCtrl;
use App\Http\Controllers\DashCtrl; 
use App\Http\Controllers\AuthCtrl;
use App\Http\Controllers\ManpowerCtrl;

Route::get('/', [WikiCtrl::class, 'index'])->name('wiki.index');
Route::get('/topic/{slug}', [WikiCtrl::class, 'show'])->name('wiki.show');

Route::get('/login', [AuthCtrl::class, 'login'])->name('login');
Route::post('/login', [AuthCtrl::class, 'auth'])->name('auth.check');
Route::post('/logout', [AuthCtrl::class, 'logout'])->name('logout');

Route::middleware('auth')->prefix('dash')->group(function(){
    Route::get('/', [DashCtrl::class, 'index'])->name('dash.index');
    Route::get('/wiki', [DashCtrl::class, 'wikiMgr'])->name('dash.wiki');
    Route::get('/data', [DashCtrl::class, 'dataMgr'])->name('dash.data');
    
    Route::get('/manpower/check-nrp', [ManpowerCtrl::class, 'checkNrp'])->name('manpower.check-nrp');
    Route::post('/manpower/log', [ManpowerCtrl::class, 'logDaily'])->name('manpower.log');
    Route::get('/manpower/log/{id}', [ManpowerCtrl::class, 'showLog'])->name('manpower.log.show');
    Route::get('/manpower/log/{id}/edit', [ManpowerCtrl::class, 'editLog'])->name('manpower.log.edit');
    Route::put('/manpower/log/{id}', [ManpowerCtrl::class, 'updateLog'])->name('manpower.log.update');
    
    Route::resource('manpower', ManpowerCtrl::class);
});