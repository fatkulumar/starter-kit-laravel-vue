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

//admin
require __DIR__.'/admin/user/view/user.php';
require __DIR__.'/admin/user/api/user.php';

require __DIR__.'/admin/event/view/event.php';
require __DIR__.'/admin/event/api/event.php';

require __DIR__.'/admin/tryout/view/tryout.php';
require __DIR__.'/admin/tryout/api/tryout.php';

require __DIR__.'/grade/api/grade.php';

require __DIR__.'/admin/order/api/order.php';
require __DIR__.'/admin/order/view/order.php';

require __DIR__.'/admin/subtest/api/subtest.php';
require __DIR__.'/admin/subtest/view/subtest.php';

require __DIR__.'/admin/question/api/question.php';
require __DIR__.'/admin/question/view/question.php';

require __DIR__.'/subject/api/subject.php';
