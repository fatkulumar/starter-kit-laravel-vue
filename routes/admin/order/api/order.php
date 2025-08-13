<?php

use App\Http\Controllers\Admin\Order\Api\OrderController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/dashboard')->as('api.')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::resource('order', OrderController::class)->only(['index']);
    Route::post('order/gift-tryout', [OrderController::class, 'giftTryout'])->name('order.gift.tryout');
    Route::get('order/has-order', [OrderController::class, 'hasOrderTryout'])->name('order.has.order.tryout');
});
