<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;

use App\Http\Controllers\BannerController;
use App\Http\Controllers\SobreHomeController;
use App\Http\Controllers\AdminUsuarioController;


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
    return redirect()->route('admin.index');
});

Route::prefix('admin')->group(function () {
    
    // rotas públicas (sem auth)
    Route::get('/login', [AdminAuthController::class, 'showLogin'])
        ->name('admin.login');

    Route::post('/login', [AdminAuthController::class, 'login'])
        ->middleware('throttle:admin-login')
        ->name('admin.login.submit');

    Route::post('/logout', [AdminAuthController::class, 'logout'])
        ->name('admin.logout');

        
    Route::middleware(['admin.auth', 'admin.loginlog'])->group(function () {

            // Home do admin (admin.index)
            Route::get('/', function () {
                return view('admin.index');
            })->name('admin.index');

            
            // perfil / senha (admin e editor)
            Route::get('/perfil/senha', [AdminAuthController::class, 'editPassword'])
                ->name('admin.perfil.senha');

            Route::post('/perfil/senha', [AdminAuthController::class, 'updatePassword'])
                ->name('admin.perfil.senha.update');
                
            // BANNERS → admin e editor
            Route::middleware('admin.level:admin,editor')->group(function () {

                Route::get('/banners', [BannerController::class, 'adminIndex'])
                    ->name('admin.banners.index');

                Route::get('/banners/create', [BannerController::class, 'adminCreate'])
                    ->name('admin.banners.create');

                Route::post('/banners/store', [BannerController::class, 'adminStore'])
                    ->name('admin.banners.store');

                Route::get('/banners/{id}/edit', [BannerController::class, 'adminEdit'])
                    ->name('admin.banners.edit');

                Route::post('/banners/{id}/update', [BannerController::class, 'adminUpdate'])
                    ->name('admin.banners.update');

                Route::post('/banners/{id}/destroy', [BannerController::class, 'adminDestroy'])
                    ->name('admin.banners.destroy');
            });

            // SOBRE → admin e editor
            Route::middleware('admin.level:admin,editor')->group(function () {
                Route::get('/sobre/edit', [SobreHomeController::class, 'adminEdit'])
                    ->name('admin.sobre.edit');

                Route::post('/sobre/update', [SobreHomeController::class, 'adminUpdate'])
                    ->name('admin.sobre.update');
            });

            // USUÁRIOS → SOMENTE admin 
            Route::middleware('admin.level:admin')->group(function () {
                                
                Route::get('/usuarios', [AdminUsuarioController::class, 'index'])
                    ->name('admin.usuarios.index');

                Route::get('/usuarios/create', [AdminUsuarioController::class, 'create'])
                    ->name('admin.usuarios.create');

                Route::post('/usuarios/store', [AdminUsuarioController::class, 'store'])
                    ->name('admin.usuarios.store');

                Route::get('/usuarios/{id}/edit', [AdminUsuarioController::class, 'edit'])
                    ->name('admin.usuarios.edit');

                Route::post('/usuarios/{id}/update', [AdminUsuarioController::class, 'update'])
                    ->name('admin.usuarios.update');

                Route::post('/usuarios/{id}/delete', [AdminUsuarioController::class, 'delete'])
                    ->name('admin.usuarios.delete');

                Route::get('/usuarios/{id}/logs', [AdminUsuarioController::class, 'logs'])
                    ->name('admin.usuarios.logs');

            });

    });

});
