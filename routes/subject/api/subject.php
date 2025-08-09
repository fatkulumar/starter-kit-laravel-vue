<?php

use App\Http\Controllers\Subject\Api\SubjectController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/dashboard')->as('api.')->middleware(['auth', 'verified'])->group(function () {
    Route::resource('subject', SubjectController::class)->only(['index']);
});