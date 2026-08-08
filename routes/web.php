<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate']);

    Route::get('/register', [AuthController::class, 'create'])->name('register');
    Route::post('/register', [AuthController::class, 'store']);
});

Route::post('/logout', [AuthController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::get('/pending-approval', function () {
    return view('auth.pending');
})->middleware('auth')->name('pending-approval');

Route::prefix('student')->name('student.')->middleware(['auth', 'approved', 'role:student_intern'])->group(function () {
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

Route::prefix('dean')->name('dean.')->middleware(['auth', 'approved', 'role:dean'])->group(function () {
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
