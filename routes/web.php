<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckinController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\PublicRegistrationController;

Route::get('/', function () {
    return view('form');
});
Route::post('/rsvp', [PublicRegistrationController::class, 'store'])
    ->middleware(['throttle:10,1']) // cegah spam iseng
    ->name('rsvp.store');

Route::get('/t/{code}', [TicketController::class, 'show'])->name('ticket.show');
Route::get('/t/{code}/qr.png', [TicketController::class, 'image'])->name('ticket.qr');

Route::middleware(['auth', 'can:Checkin:Scan'])->group(function () {
    Route::view('/checkin', 'checkin.index')->name('checkin');
    Route::post('/checkin/verify', [CheckinController::class, 'verify']);
    Route::get('/checkin/cache.json', [CheckinController::class, 'cacheTokens']);
    Route::post('/checkin/sync', [CheckinController::class, 'syncOffline']);
});

