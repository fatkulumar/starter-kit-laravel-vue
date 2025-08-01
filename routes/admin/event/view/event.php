<?php

use App\Http\Controllers\Admin\Event\View\EventController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/dashboard')->as('admin.')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('event', EventController::class);
});