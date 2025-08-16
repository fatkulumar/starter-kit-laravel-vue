<?php

use App\Http\Controllers\Student\Tryout\View\TryoutController;
use Illuminate\Support\Facades\Route;

Route::prefix('student')->as('student.')->group(function () {
    Route::get('tryout/{eventCode}', TryoutController::class)->name('tryout');
});