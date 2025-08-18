<?php

use App\Http\Controllers\Student\StartTryout\View\StartTryoutController;
use Illuminate\Support\Facades\Route;

Route::prefix('student')->as('student.')->group(function () {
    Route::get('tryout/start/{tryoutCode}', [StartTryoutController::class, 'startTryout'])->name('start.tryout');
});