<?php

use App\Http\Controllers\Admin\Purchase\View\PurchaseController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/dashboard')->as('admin.')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('purchase', PurchaseController::class);
});