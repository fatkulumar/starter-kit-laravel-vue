<?php

use App\Http\Controllers\Admin\Grade\Api\GradeController;
use Illuminate\Support\Facades\Route;

Route::prefix('apiadmin/dashboard')->as('api.')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::resource('grade', GradeController::class)->only(['index']);
});