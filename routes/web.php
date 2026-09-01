<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ParticipantRegistrationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TripController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware([
    'auth',
    'role:admin,staff',
])->group(function () {
    Route::get('/dashboard', [
        DashboardController::class,
        'index',
    ])->name('dashboard');

    Route::resource('trips', TripController::class);

    Route::get(
        '/trips/{trip}/participants/create',
        [
            ParticipantRegistrationController::class,
            'create',
        ]
    )->name('trips.participants.create');

    Route::post(
        '/trips/{trip}/participants',
        [
            ParticipantRegistrationController::class,
            'store',
        ]
    )->name('trips.participants.store');

    Route::patch(
        '/trips/{trip}/participants/{participant}/payment',
        [
            ParticipantRegistrationController::class,
            'updatePayment',
        ]
    )->name('trips.participants.payment');

    Route::delete(
        '/trips/{trip}/participants/{participant}',
        [
            ParticipantRegistrationController::class,
            'destroy',
        ]
    )->name('trips.participants.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';