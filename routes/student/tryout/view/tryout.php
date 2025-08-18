<?php

use App\Http\Controllers\Student\Tryout\View\TryoutController;
use Illuminate\Support\Facades\Route;

Route::prefix('student')->as('student.')->group(function () {
    Route::get('tryout/{eventCode}', [TryoutController::class, 'index'])->name('tryout');
    Route::get('tryout/doing/{tryoutCode}', [TryoutController::class, 'doingTryout'])->name('doing.tryout');
});