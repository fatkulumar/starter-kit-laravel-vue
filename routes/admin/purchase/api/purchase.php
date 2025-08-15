<?php

use App\Http\Controllers\Admin\Purchase\Api\PurchaseController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/dashboard')->as('api.')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::resource('purchase', PurchaseController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::post('purchase/delete-all', [PurchaseController::class, 'deleteAll'])->name('purchase.delete.all');
    Route::post('purchase/confirm', [PurchaseController::class, 'confirm'])->name('purchase.confirm');
    Route::post('purchase/confirmation-all', [PurchaseController::class, 'confirmationAll'])->name('purchase.confirmation.all');
});