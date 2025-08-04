<?php

use App\Http\Controllers\Admin\Order\Api\OrderController;
use Illuminate\Support\Facades\Route;

Route::prefix('apiadmin/dashboard')->as('api.')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::resource('order', OrderController::class)->only(['index', 'store']);
});
