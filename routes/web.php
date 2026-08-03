<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MasterdataController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\PerbaikanController;

/*
|--------------------------------------------------------------------------
| Guest Routes (Hanya untuk yang BELUM login)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::get('/login', 'loginForm')->name('login');
        Route::post('/login', 'login')->name('login.post');
    });
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Hanya untuk yang SUDAH login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard & Custom Kendaraan Route
    Route::controller(KendaraanController::class)->group(function () {
        Route::get('/', 'index')->name('dashboard');
        Route::get('/list', 'list')->name('list');
    });

    // Resource Routes
    Route::resource('kendaraan', KendaraanController::class);
    Route::resource('perbaikan', PerbaikanController::class);

    // Group Master Data
    Route::prefix('masterdata')->name('masterdata.')->group(function () {
        
        // Master Data User
        Route::get('/user', [MasterdataController::class, 'user'])->name('user');
        Route::post('/user', [MasterdataController::class, 'userStore'])->name('user.store');
        Route::put('/user/{user}', [MasterdataController::class, 'userUpdate'])->name('user.update');
        Route::delete('/user/{user}', [MasterdataController::class, 'userDestroy'])->name('user.destroy');

        // Master Data Departemen
        Route::get('/departemen', [MasterdataController::class, 'departemen'])->name('departemen');
        Route::post('/departemen', [MasterdataController::class, 'departemenStore'])->name('departemen.store');
        Route::put('/departemen/{departemen}', [MasterdataController::class, 'departemenUpdate'])->name('departemen.update');
        Route::delete('/departemen/{departemen}', [MasterdataController::class, 'departemenDestroy'])->name('departemen.destroy');
    });

});