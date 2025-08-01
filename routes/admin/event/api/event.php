<?php

use App\Http\Controllers\Admin\Event\Api\EventController;
use Illuminate\Support\Facades\Route;

Route::prefix('apiadmin/dashboard')->as('api.')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::resource('event', EventController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::post('apiadmin/dashboard/event/delete-all', [EventController::class, 'deleteAll'])->name('event.delete.all');
});