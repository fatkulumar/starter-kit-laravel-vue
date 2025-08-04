<?php

use App\Http\Controllers\Admin\Order\View\OrderController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/dashboard')->as('admin.')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('order', OrderController::class);
});