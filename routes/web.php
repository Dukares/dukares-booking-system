<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SecurityController;

use App\Http\Controllers\ChannelController;
use App\Http\Controllers\HostChannelController;
use App\Http\Controllers\ICSController;
use App\Http\Controllers\ICSExportController;

// -----------------------------------------
// ⭐ Rotta ICS PUBBLICA (senza login) — EXPORT ICS
// -----------------------------------------
Route::get('/ics/property/{id}.ics', 
    [ICSExportController::class, 'export']
)->name('ics.export');
// -----------------------------------------


Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', function () {
    return view('auth.login');
})->middleware('guest')->name('login');

Route::post('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/login');
})->name('logout');


Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // -----------------------------------------
    // PROPRIETARI
    // -----------------------------------------
    Route::resource('/owners', OwnerController::class);

    // -----------------------------------------
    // STRUTTURE
    // -----------------------------------------
    Route::resource('/properties', PropertyController::class);

    Route::get('/properties/{property}/calendar',
        [PropertyController::class, 'calendar']
    )->name('properties.calendar');

    Route::post('/calendar/update-day',
        [PropertyController::class, 'updateDay']
    )->name('calendar.updateDay');

    Route::post('/calendar/update-range',
        [PropertyController::class, 'updateRange']
    )->name('calendar.updateRange');

    // -----------------------------------------
    // ⭐ CALENDARIO AJAX — DISPONIBILITÀ LIVE
    // -----------------------------------------
    Route::get('/calendar/month/{property}', 
        [PropertyController::class, 'loadMonth']
    )->name('calendar.loadMonth');


    // -----------------------------------------
    // ⭐ CHANNEL MANAGER — PAGINA PER STRUTTURA
    // -----------------------------------------
    Route::get('/properties/{property}/channel-manager',
        [PropertyController::class, 'channelManager']
    )->name('properties.channelManager');

    // -----------------------------------------
    // ⭐ CHANNEL MANAGER — SYNC NOW (import ICS live)
    // -----------------------------------------
    Route::post('/properties/{property}/sync-now',
        [PropertyController::class, 'syncNow']
    )->name('properties.syncNow');


    // -----------------------------------------
    // PRENOTAZIONI
    // -----------------------------------------
    Route::resource('/reservations', ReservationController::class);

    // -----------------------------------------
    // SICUREZZA
    // -----------------------------------------
    Route::get('/security', [SecurityController::class, 'index'])
        ->name('security');

    // -----------------------------------------
    // CHANNELS (admin)
    // -----------------------------------------
    Route::resource('/channels', ChannelController::class)
        ->except(['show']);

    // -----------------------------------------
    // CHANNELS (host)
    // -----------------------------------------
    Route::get('/host/channels',
        [HostChannelController::class, 'index']
    )->name('host.channels.index');

    Route::post('/host/channels',
        [HostChannelController::class, 'storeOrUpdate']
    )->name('host.channels.store');


    // -----------------------------------------
    // ⭐ ICS TEST MANUALE
    // -----------------------------------------
    Route::get('/ics-test', [ICSController::class, 'testForm'])->name('ics.test');
    Route::post('/ics-test', [ICSController::class, 'runTest'])->name('ics.test.run');

});

