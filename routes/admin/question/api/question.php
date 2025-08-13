<?php

use App\Http\Controllers\Admin\Question\Api\QuestionController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/dashboard')->as('api.')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::resource('question', QuestionController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::post('question/delete-all', [QuestionController::class, 'deleteAll'])->name('question.delete.all');
});