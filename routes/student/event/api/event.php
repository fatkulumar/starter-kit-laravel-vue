<?php

use App\Http\Controllers\Student\Event\Api\EventController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/student')->as('api.')->middleware(['auth', 'verified', 'role:student'])->group(function () {
    Route::get('get-event-purchased', [EventController::class, 'getEventPurchased'])->name('get.event.purchased');
});