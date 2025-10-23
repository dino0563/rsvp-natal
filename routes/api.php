<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FonnteWebhookController;

Route::post('/webhooks/fonnte', [FonnteWebhookController::class, 'handle'])
    ->name('webhooks.fonnte');

    Route::get('/ping-api', fn() => response()->json(['pong' => base_path()]));
