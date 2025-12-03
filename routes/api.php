<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{PubCtrl, AuthCtrl, AdmCtrl, CmsCtrl};

// Public Interface (Guest Access, Read-Only)
Route::get('/wiki', [PubCtrl::class, 'list']);      // List all wiki pages
Route::get('/wiki/{slug}', [PubCtrl::class, 'show']); // Read specific page
Route::post('/login', [AuthCtrl::class, 'in']);     // Admin login

// Restricted Admin Area (Auth Required, Recapitulation & CMS)
Route::middleware('auth:sanctum')->prefix('adm')->group(function () {
    Route::get('/stats', [AdmCtrl::class, 'recap']); // Data recapitulation
    Route::post('/logout', [AuthCtrl::class, 'out']);
    Route::apiResource('docs', CmsCtrl::class);      // CMS CRUD operations
});