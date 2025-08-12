<?php

use App\Http\Controllers\Student\Purchase\Api\PurchaseController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/student')->as('api.')->group(function () {
    Route::post('purchase', [PurchaseController::class, 'store'])->name('purchase');
});