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
