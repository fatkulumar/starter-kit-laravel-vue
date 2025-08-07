<?php

use App\Http\Controllers\Admin\Subtest\View\SubtestController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/dashboard')->as('admin.')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('subtest', SubtestController::class);
});