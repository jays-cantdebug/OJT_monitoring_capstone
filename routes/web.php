<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/pending-approval', function () {
    return view('auth.pending');
})->name('pending-approval');

Route::prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', function () {
        return view('student.dashboard');
    })->name('dashboard');

    Route::get('/time', function () {
        return view('student.time');
    })->name('time');

    Route::get('/reports', function () {
        return view('student.reports');
    })->name('reports');

    Route::get('/attendance', function () {
        return view('student.attendance');
    })->name('attendance');

    Route::get('/internship-info', function () {
        return view('student.internship-info');
    })->name('internship-info');

    Route::get('/notifications', function () {
        return view('student.notifications');
    })->name('notifications');

    Route::get('/profile', function () {
        return view('student.profile');
    })->name('profile');
});

Route::prefix('dean')->name('dean.')->group(function () {
    Route::get('/dashboard', function () {
        return view('dean.dashboard');
    })->name('dashboard');

    Route::get('/live-map', function () {
        return view('dean.live-map');
    })->name('live-map');

    Route::get('/pending-approvals', function () {
        return view('dean.pending-approvals');
    })->name('pending-approvals');

    Route::get('/students', function () {
        return view('dean.students');
    })->name('students');

    Route::get('/attendance', function () {
        return view('dean.attendance');
    })->name('attendance');

    Route::get('/reports', function () {
        return view('dean.reports');
    })->name('reports');

    Route::get('/reports-export', function () {
        return view('dean.reports-export');
    })->name('reports-export');

    Route::get('/notifications', function () {
        return view('dean.notifications');
    })->name('notifications');

    Route::get('/profile', function () {
        return view('dean.profile');
    })->name('profile');
});
