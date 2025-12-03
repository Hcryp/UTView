<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WikiCtrl;
use App\Http\Controllers\AdmCtrl;
use App\Http\Controllers\AuthCtrl;

/*
|--------------------------------------------------------------------------
| Public Wiki Interface (Read-Only, No Auth)
|--------------------------------------------------------------------------
*/
Route::get('/', [WikiCtrl::class, 'idx'])->name('wiki.idx');
Route::get('/wiki/{slug}', [WikiCtrl::class, 'read'])->name('wiki.read');
Route::get('/search', [WikiCtrl::class, 'find'])->name('wiki.find');

/*
|--------------------------------------------------------------------------
| Authentication (Guest Only)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthCtrl::class, 'form'])->name('login');
    Route::post('/login', [AuthCtrl::class, 'chk'])->name('auth.chk');
});

Route::post('/logout', [AuthCtrl::class, 'out'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Restricted Admin Dashboard (Auth Required)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('adm')->name('adm.')->group(function () {
    // Dashboard & Recapitulation
    Route::get('/', [AdmCtrl::class, 'dash'])->name('dash');
    Route::get('/recap', [AdmCtrl::class, 'recap'])->name('recap');

    // CMS Operations (CRUD)
    Route::post('/doc', [AdmCtrl::class, 'add'])->name('add');
    Route::put('/doc/{id}', [AdmCtrl::class, 'mod'])->name('mod');
    Route::delete('/doc/{id}', [AdmCtrl::class, 'del'])->name('del');
});