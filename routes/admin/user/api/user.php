<?php

use App\Http\Controllers\Admin\User\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/dashboard')->as('api.')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::resource('user', UserController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::post('user/delete-all', [UserController::class, 'deleteAll'])->name('user.delete.all');
    Route::get('user/not-has-tryout', [UserController::class, 'userNotHasTryout'])->name('user.not.has.tryout');
});