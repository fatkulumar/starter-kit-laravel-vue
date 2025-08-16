<?php

use App\Http\Controllers\Student\Tryout\Api\TryoutController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/student')->as('api.')->group(function () {
    Route::get('tryout-by-event-id', [TryoutController::class, 'getTryoutByEventId'])->name('tryout.by.event.id');
    Route::get('tryout-purchased-by-event-id', [TryoutController::class, 'getTryoutPurchasedByEventId'])->name('tryout.purchased.by.event.id');
});