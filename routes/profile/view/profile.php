<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('profile', ProfileController::class)->middleware(['auth', 'verified'])->name('home.profile');