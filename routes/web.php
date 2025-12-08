<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SecurityController;

/*
|--------------------------------------------------------------------------
| WEB ROUTES - DukaRes
|--------------------------------------------------------------------------
*/

/* HOME → redirect login */
Route::get('/', function () {
    return redirect('/login');
});

/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/
Route::get('/login', function () {
    return view('auth.login');
})->middleware('guest')->name('login');

/*
|--------------------------------------------------------------------------
| LOGOUT (funzionante senza errore 419)
|--------------------------------------------------------------------------
*/
Route::post('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/login');
})->name('logout');

/*
|--------------------------------------------------------------------------
| AREA PROTETTA (serve autenticazione)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /* Dashboard */
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /* Proprietari */
    Route::resource('/owners', OwnerController::class);

    /* Strutture */
    Route::resource('/properties', PropertyController::class);

    /* ⭐ PMS Calendar per una singola struttura */
    Route::get('/properties/{property}/calendar', 
        [PropertyController::class, 'calendar']
    )->name('properties.calendar');

    /* ⭐ Singolo giorno calendario */
    Route::post('/calendar/update-day', 
        [PropertyController::class, 'updateDay']
    )->name('calendar.updateDay');

    /* ⭐⭐ Range selection calendario (NUOVO) */
    Route::post('/calendar/update-range', 
        [PropertyController::class, 'updateRange']
    )->name('calendar.updateRange');

    /* Prenotazioni */
    Route::resource('/reservations', ReservationController::class);

    /* Security Center */
    Route::get('/security', [SecurityController::class, 'index'])
        ->name('security');
});
