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
|--------------------------------------------------------------------------
| ROTTE PUBBLICHE
|--------------------------------------------------------------------------
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
|--------------------------------------------------------------------------
| ROTTE AUTENTICATE (STABILI)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /*
    |----------------------------------------------------------------------
    | Dashboard & Security
    |----------------------------------------------------------------------
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/security', [SecurityController::class, 'index'])
        ->name('security');

    /*
    |----------------------------------------------------------------------
    | PROPERTIES (CRUD COMPLETO)
    |----------------------------------------------------------------------
    */
    Route::resource('properties', PropertyController::class);

    /*
    |----------------------------------------------------------------------
    | OWNERS (CRUD BASE – per ora)
    |----------------------------------------------------------------------
    */
    Route::get('/owners', [OwnerController::class, 'index'])
        ->name('owners.index');

    Route::get('/owners/create', [OwnerController::class, 'create'])
        ->name('owners.create');

    Route::post('/owners', [OwnerController::class, 'store'])
        ->name('owners.store');

    /*
    |----------------------------------------------------------------------
    | RESERVATIONS (placeholder)
    |----------------------------------------------------------------------
    */
    Route::get('/reservations', [ReservationController::class, 'index'])
        ->name('reservations.index');

    /*
    |----------------------------------------------------------------------
    | CHANNELS
    |----------------------------------------------------------------------
    */
    Route::get('/channels', [ChannelController::class, 'index'])
        ->name('channels.index');

    Route::get('/host/channels', [HostChannelController::class, 'index'])
        ->name('host.channels.index');
});


