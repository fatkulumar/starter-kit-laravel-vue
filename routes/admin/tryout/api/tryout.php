<?php

use App\Http\Controllers\Admin\Tryout\Api\TryoutController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/dashboard')->as('api.')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::resource('tryout', TryoutController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::post('api/dashboard/tryout/delete-all', [TryoutController::class, 'deleteAll'])->name('tryout.delete.all');
});