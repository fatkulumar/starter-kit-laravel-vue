<?php

use App\Http\Controllers\Admin\Tryout\View\TryoutController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/dashboard')->as('admin.')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('tryout', [TryoutController::class, 'index'])->name('tryout.index');
    Route::get('tryout/gift', [TryoutController::class, 'gift'])->name('tryout.gift');
});
