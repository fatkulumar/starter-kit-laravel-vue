<?php

use App\Http\Controllers\Admin\Question\View\QuestionController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/dashboard')->as('admin.')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('question', QuestionController::class);
});