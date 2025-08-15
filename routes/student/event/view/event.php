<?php

use App\Http\Controllers\Student\Event\View\EventController;
use Illuminate\Support\Facades\Route;

Route::prefix('student')->as('student.')->middleware(['auth', 'verified', 'role:student'])->group(function () {
    Route::get('event', EventController::class);
});