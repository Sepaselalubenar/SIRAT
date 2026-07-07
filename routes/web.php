<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RoomController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\Auth\DosenLoginController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\HistoryController;

/*
|--------------------------------------------------------------------------
| Auth routes (dosen & admin punya login terpisah)
|--------------------------------------------------------------------------
*/

Route::get('/', [DosenLoginController::class, 'create'])->name('login');
Route::post('/login', [DosenLoginController::class, 'store'])->name('login.store');
Route::post('/logout', [DosenLoginController::class, 'destroy'])->name('logout');

Route::get('/admin/login', [AdminLoginController::class, 'create'])->name('admin.login');
Route::post('/admin/login', [AdminLoginController::class, 'store'])->name('admin.login.store');
Route::post('/admin/logout', [AdminLoginController::class, 'destroy'])->name('admin.logout');

/*
|--------------------------------------------------------------------------
| Dosen area (butuh login role: dosen)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:dosen'])->group(function () {
    Route::get('/dashboard', function () {
        return view('lecturer.dashboard');
    });

    Route::get('/history', [HistoryController::class, 'index']);

    Route::get('/reservation', [RoomController::class, 'index']);
    Route::get('/reservation/detail/{id}', [RoomController::class, 'show']);
    Route::post('/reservation/store', [ReservationController::class, 'store']);
});

/*
|--------------------------------------------------------------------------
| Admin area (butuh login role: admin)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', [DashboardController::class, 'index']);
});
