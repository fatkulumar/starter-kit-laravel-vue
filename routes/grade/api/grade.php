<?php

use App\Http\Controllers\Grade\Api\GradeController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/dashboard')->as('api.')->middleware(['auth', 'verified'])->group(function () {
    Route::resource('grade', GradeController::class)->only(['index']);
});