<?php

use App\Http\Controllers\Admin\User\View\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/dashboard')->as('admin.')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('user', UserController::class);
});