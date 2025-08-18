<?php

use App\Http\Controllers\Student\StartTryout\Api\StartTryoutController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/student')->as('api.')->group(function () {
    Route::post('tryout/start', [StartTryoutController::class, 'startTryout'])->name('start.tryout');
});