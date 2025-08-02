<?php

use App\Http\Controllers\Admin\Tryout\Api\TryoutController;
use Illuminate\Support\Facades\Route;

Route::prefix('apiadmin/dashboard')->as('api.')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::resource('tryout', TryoutController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::post('apiadmin/dashboard/tryout/delete-all', [TryoutController::class, 'deleteAll'])->name('tryout.delete.all');
});