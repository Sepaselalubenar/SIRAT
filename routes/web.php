<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RoomController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\Auth\DosenLoginController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RoomManagementController;
use App\Http\Controllers\Admin\ReservationManagementController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\LecturerDashboardController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\Admin\UserManagementController;

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

Route::middleware(['auth', 'role:dosen,pegawai'])->group(function () {
    Route::get('/dashboard', [LecturerDashboardController::class, 'index']);

    Route::get('/history', [HistoryController::class, 'index']);

    Route::get('/reservation', [RoomController::class, 'index']);
    Route::get('/reservation/detail/{id}', [RoomController::class, 'show']);
    Route::post('/reservation/store', [ReservationController::class, 'store']);
    Route::post('/reservation/{id}/cancel', [ReservationController::class, 'cancelUser'])->name('reservation.cancel');

    // Calendar
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::get('/calendar/events', [CalendarController::class, 'events'])->name('calendar.events');
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
    Route::post('/admin/rooms/reserve', [RoomManagementController::class, 'storeReservation'])->name('admin.rooms.reserve');

    // Reservation approval
    Route::post('/admin/reservations/{id}/approve', [DashboardController::class, 'approve'])->name('admin.reservations.approve');
    Route::post('/admin/reservations/{id}/reject', [DashboardController::class, 'reject'])->name('admin.reservations.reject');

    // Reservation management
    Route::get('/admin/reservations', [ReservationManagementController::class, 'index'])->name('admin.reservations.index');
    Route::post('/admin/reservations/{id}/cancel', [ReservationManagementController::class, 'cancel'])->name('admin.reservations.cancel');
    Route::delete('/admin/reservations/{id}', [ReservationManagementController::class, 'destroy'])->name('admin.reservations.destroy');

    // Calendar (admin can also access dosen calendar view)
    Route::get('/admin/calendar', [CalendarController::class, 'index'])->name('admin.calendar.index');
    Route::get('/admin/calendar/events', [CalendarController::class, 'events'])->name('admin.calendar.events');

    // User management (dosen)
    Route::get('/admin/users', [UserManagementController::class, 'index'])->name('admin.users.index');
    Route::post('/admin/users', [UserManagementController::class, 'store'])->name('admin.users.store');
    Route::put('/admin/users/{user}', [UserManagementController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [UserManagementController::class, 'destroy'])->name('admin.users.destroy');
});
