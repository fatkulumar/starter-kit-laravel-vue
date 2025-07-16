<?php

use App\Http\Controllers\Admin\User\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('apiadmin/dashboard')->as('apiadmin.')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::resource('user', UserController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::post('apiadmin/dashboard/user/delete-all', [UserController::class, 'deleteAll'])->name('user.delete.all');
});