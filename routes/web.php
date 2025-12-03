<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WikiCtrl;
use App\Http\Controllers\AdmCtrl;

// PUBLIC WIKI (Open Access)
Route::controller(WikiCtrl::class)->group(function () {
    Route::get('/', 'home')->name('wiki.home');
    Route::get('/read/{slug}', 'read')->name('wiki.read');
});

// ADMIN AUTH (Guest Only)
Route::middleware('guest')->controller(AdmCtrl::class)->group(function () {
    Route::get('/login', 'login')->name('login');
    Route::post('/signin', 'signin')->name('auth.signin');
});

// ADMIN DASHBOARD (Protected)
Route::middleware('auth')->prefix('adm')->controller(AdmCtrl::class)->group(function () {
    Route::get('/', 'dash')->name('adm.dash');
    Route::post('/save', 'save')->name('adm.save');
    Route::post('/out', 'out')->name('adm.out');
});