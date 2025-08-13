<?php

use App\Http\Controllers\Public\Event\Api\EventController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/public')->as('api.')->group(function () {
    Route::get('event', [EventController::class, 'getEventPublish'])->name('public.event');
});