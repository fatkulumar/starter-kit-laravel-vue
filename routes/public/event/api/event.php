<?php

use App\Http\Controllers\Public\Event\Api\EventController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/public')->as('api.')->group(function () {
    Route::resource('event', EventController::class)->only(['index']);
});