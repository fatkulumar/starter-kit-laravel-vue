<?php

use App\Http\Controllers\Public\Tryout\Api\TryoutController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/public')->as('api.')->group(function () {
    Route::get('tryout-by-event-id', [TryoutController::class, 'getTryoutByEventId'])->name('tryout.by.event.id');
});