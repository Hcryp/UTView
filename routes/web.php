<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WikiCtrl;
use App\Http\Controllers\AdmCtrl;
use App\Http\Controllers\AuthCtrl;

// Public Wiki Routes (Read-Only, No Auth)
Route::controller(WikiCtrl::class)->group(function () {
    Route::get('/', 'home')->name('wiki.home');
    Route::get('/wiki', 'index')->name('wiki.list');
    Route::get('/wiki/{slug}', 'show')->name('wiki.show');
});

// Authentication Routes (Guest Only)
Route::controller(AuthCtrl::class)->middleware('guest')->group(function () {
    Route::get('/login', 'form')->name('login');
    Route::post('/login', 'login')->name('auth.login');
});

// Admin Dashboard Routes (Protected, Username Auth)
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::controller(AuthCtrl::class)->group(function () {
        Route::post('/logout', 'logout')->name('auth.logout');
    });

    Route::controller(AdmCtrl::class)->name('admin.')->group(function () {
        Route::get('/', 'dash')->name('dash');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::put('/update/{id}', 'update')->name('update');
        Route::delete('/delete/{id}', 'destroy')->name('destroy');
    });
});