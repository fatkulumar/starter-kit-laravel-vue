<?php

use App\Http\Controllers\Admin\Subtest\Api\SubtestController;
use Illuminate\Support\Facades\Route;

Route::prefix('apiadmin/dashboard')->as('api.')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::resource('subtest', SubtestController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::post('subtest/delete-all', [SubtestController::class, 'deleteAll'])->name('subtest.delete.all');
});