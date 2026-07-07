<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RoomController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\Auth\DosenLoginController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RoomManagementController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\LecturerDashboardController;

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
    Route::get('/dashboard', [LecturerDashboardController::class, 'index']);

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
    
    // Room management
    Route::get('/admin/rooms', [RoomManagementController::class, 'index'])->name('admin.rooms.index');
    Route::post('/admin/rooms', [RoomManagementController::class, 'store'])->name('admin.rooms.store');
    Route::put('/admin/rooms/{id}', [RoomManagementController::class, 'update'])->name('admin.rooms.update');
    Route::delete('/admin/rooms/{id}', [RoomManagementController::class, 'destroy'])->name('admin.rooms.destroy');
    Route::post('/admin/rooms/{id}/photos', [RoomManagementController::class, 'uploadPhoto'])->name('admin.rooms.photos.upload');
    Route::delete('/admin/rooms/photos/{photoId}', [RoomManagementController::class, 'deletePhoto'])->name('admin.rooms.photos.delete');

    // Reservation approval
    Route::post('/admin/reservations/{id}/approve', [DashboardController::class, 'approve'])->name('admin.reservations.approve');
    Route::post('/admin/reservations/{id}/reject', [DashboardController::class, 'reject'])->name('admin.reservations.reject');
});
