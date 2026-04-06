<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\SobreHomeController;

// ========================
// SITE
// ========================
Route::get('/', function () {
    return redirect()->route('site.home');
});

Route::prefix('site')->group(function () {
    Route::get('/home', [BannerController::class, 'siteHome'])
        ->name('site.home');
});

// ========================
// ADMIN
// ========================
Route::get('/admin', function () {
    return redirect()->route('admin.home');
});

Route::prefix('admin')->group(function () {

// banners 
    Route::get('/banners', [BannerController::class, 'adminIndex'])
        ->name('admin.banners.index');

    Route::get('/banners/create', [BannerController::class, 'adminCreate'])
        ->name('admin.banners.create');

    Route::post('/banners/store', [BannerController::class, 'adminStore'])
        ->name('admin.banners.store');

    Route::get('/banners/{id}/show', [BannerController::class, 'adminShow'])
        ->name('admin.banners.show');

    Route::get('/banners/{id}/edit', [BannerController::class, 'adminEdit'])
        ->name('admin.banners.edit');

    Route::post('/banners/{id}/update', [BannerController::class, 'adminUpdate'])
        ->name('admin.banners.update');

    Route::post('/banners/{id}/destroy', [BannerController::class, 'adminDestroy'])
        ->name('admin.banners.destroy');

// sobre (SINGLETON)
    Route::get('/sobre/edit', [SobreHomeController::class, 'adminEdit'])
        ->name('admin.sobre.edit');

    Route::post('/sobre/update', [SobreHomeController::class, 'adminUpdate'])
        ->name('admin.sobre.update');

});
