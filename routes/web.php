<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EquipmentController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Responsable\PendingBookingController;
use App\Http\Controllers\Responsable\PlanningController as ResponsablePlanningController;
use App\Http\Controllers\RoomPlanningController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $role = auth()->user()->role;

        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'responsable' => redirect()->route('responsable.pending'),
            default => redirect()->route('bookings.index'),
        };
    })->name('dashboard');

    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}/edit', [BookingController::class, 'edit'])->name('bookings.edit');
    Route::put('/bookings/{booking}', [BookingController::class, 'update'])->name('bookings.update');
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');
    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::get('/rooms/planning', [RoomPlanningController::class, 'index'])->name('rooms.planning');

    Route::middleware('role:responsable,admin')->prefix('responsable')->name('responsable.')->group(function () {
        Route::get('/pending', [PendingBookingController::class, 'index'])->name('pending');
        Route::post('/bookings/{booking}/accept', [PendingBookingController::class, 'accept'])->name('accept');
        Route::post('/bookings/{booking}/reject', [PendingBookingController::class, 'reject'])->name('reject');
        Route::get('/planning', [ResponsablePlanningController::class, 'index'])->name('planning');
    });

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('rooms', RoomController::class)->except(['show']);
        Route::resource('equipment', EquipmentController::class)->except(['show']);
        Route::resource('users', UserController::class)->except(['show']);
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
