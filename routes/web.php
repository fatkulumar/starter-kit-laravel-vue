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

Route::get('student/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified', 'role:student'])->name('student.dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

// admin user
require __DIR__.'/admin/user/view/user.php';
require __DIR__.'/admin/user/api/user.php';
// admin event
require __DIR__.'/admin/event/view/event.php';
require __DIR__.'/admin/event/api/event.php';
// admin tryout
require __DIR__.'/admin/tryout/view/tryout.php';
require __DIR__.'/admin/tryout/api/tryout.php';
// admin order
require __DIR__.'/admin/order/api/order.php';
require __DIR__.'/admin/order/view/order.php';
// admin subtest
require __DIR__.'/admin/subtest/api/subtest.php';
require __DIR__.'/admin/subtest/view/subtest.php';
// admin question
require __DIR__.'/admin/question/api/question.php';
require __DIR__.'/admin/question/view/question.php';



// public subject
require __DIR__.'/public/subject/api/subject.php';
// public grade
require __DIR__.'/public/grade/api/grade.php';
// public event
require __DIR__.'/public/event/api/event.php';
// public tryout
require __DIR__.'/public/tryout/api/tryout.php';
