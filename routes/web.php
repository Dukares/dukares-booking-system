<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SecurityController;

use App\Http\Controllers\PropertyController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\HostChannelController;

/*
|---------------------------------------------------------------------------
| ROTTE PUBBLICHE
|---------------------------------------------------------------------------
*/
Route::get('/', fn () => redirect('/login'));

Route::get('/login', fn () => view('auth.login'))
    ->middleware('guest')
    ->name('login');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest')
    ->name('login.store');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

/*
|---------------------------------------------------------------------------
| ROTTE AUTENTICATE (STABILI)
|---------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Security Center
    Route::get('/security', [SecurityController::class, 'index'])->name('security');

    // STRUTTURE (Properties) - CRUD base
    Route::get('/properties', [PropertyController::class, 'index'])->name('properties.index');
    Route::get('/properties/create', [PropertyController::class, 'create'])->name('properties.create');
    Route::post('/properties', [PropertyController::class, 'store'])->name('properties.store');

    // PROPRIETARI (Owners) - CRUD base
    Route::get('/owners', [OwnerController::class, 'index'])->name('owners.index');
    Route::get('/owners/create', [OwnerController::class, 'create'])->name('owners.create');
    Route::post('/owners', [OwnerController::class, 'store'])->name('owners.store');

    // PRENOTAZIONI (placeholder per ora, poi lo faremo CRUD)
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');

    // CANALI (Admin) - placeholder/indice
    Route::get('/channels', [ChannelController::class, 'index'])->name('channels.index');

    // HOST CHANNEL MANAGER
    Route::get('/host/channels', [HostChannelController::class, 'index'])->name('host.channels.index');
});
