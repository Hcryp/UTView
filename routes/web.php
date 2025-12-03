<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{PubCtrl, AuthCtrl, AdmCtrl, CmsCtrl};

// Public
Route::get('/', [PubCtrl::class, 'index'])->name('home');
Route::get('/wiki/{slug}', [PubCtrl::class, 'read'])->name('wiki.read');

// Auth
Route::get('/login', [AuthCtrl::class, 'form'])->name('login');
Route::post('/login', [AuthCtrl::class, 'in']);

// Admin Protected
Route::middleware('auth')->prefix('adm')->group(function() {
    Route::post('/out', [AuthCtrl::class, 'out'])->name('logout');
    Route::get('/dash', [AdmCtrl::class, 'dash'])->name('adm.dash');
    Route::resource('docs', CmsCtrl::class);
});
