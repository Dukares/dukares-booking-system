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
use App\Http\Controllers\CalendarController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
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
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard & Security
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/security', [SecurityController::class, 'index'])
        ->name('security');

    /*
    |--------------------------------------------------------------------------
    | CRUD
    |--------------------------------------------------------------------------
    */
    Route::resource('properties', PropertyController::class);
    Route::resource('owners', OwnerController::class);
    Route::resource('reservations', ReservationController::class);

    /*
    |--------------------------------------------------------------------------
    | CALENDAR – MONTH / DAY / STORE (STEP 1–2–3)
    |--------------------------------------------------------------------------
    */
    Route::get('/calendar', [CalendarController::class, 'index'])
        ->name('calendar.index');

    Route::get('/calendar/day', [CalendarController::class, 'day'])
        ->name('calendar.day');

    Route::post('/calendar/store', [CalendarController::class, 'store'])
        ->name('calendar.store');

    /*
    |--------------------------------------------------------------------------
    | CALENDAR – WEEKLY / TIMELINE (STEP 4)
    |--------------------------------------------------------------------------
    */
    Route::get('/calendar/week', [CalendarController::class, 'week'])
        ->name('calendar.week');

    /*
    |--------------------------------------------------------------------------
    | CHANNELS
    |--------------------------------------------------------------------------
    */
    Route::get('/channels', [ChannelController::class, 'index'])
        ->name('channels.index');

    Route::get('/host/channels', [HostChannelController::class, 'index'])
        ->name('host.channels.index');
});
