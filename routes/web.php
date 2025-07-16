<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('admin/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified', 'role:admin'])->name('admin.dashboard');

Route::get('member/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified', 'role:member'])->name('member.dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

require __DIR__.'/admin/user/view/user.php';
require __DIR__.'/admin/user/api/user.php';
