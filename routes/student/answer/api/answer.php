<?php

use App\Http\Controllers\Student\Answer\Api\AnswerController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/student')->as('api.')->group(function () {
    Route::resource('answer', AnswerController::class)->only(['index', 'store']);
});