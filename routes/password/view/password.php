<?php

use App\Http\Controllers\PasswordController;
use Illuminate\Support\Facades\Route;

Route::get('password', PasswordController::class)->middleware(['auth', 'verified'])->name('home.password');