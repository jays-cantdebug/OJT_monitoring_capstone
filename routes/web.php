<?php

use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DeanAccountController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Dean\AccomplishmentReportController as DeanAccomplishmentReportController;
use App\Http\Controllers\Dean\AttendanceController;
use App\Http\Controllers\Dean\DashboardController as DeanDashboardController;
use App\Http\Controllers\Dean\LiveMapController;
use App\Http\Controllers\Dean\NotificationController as DeanNotificationController;
use App\Http\Controllers\Dean\PendingApprovalController;
use App\Http\Controllers\Dean\ReportExportController;
use App\Http\Controllers\Dean\StudentAccountController;
use App\Http\Controllers\Dean\StudentProfileController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Student\AccomplishmentReportController;
use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Student\DtrController;
use App\Http\Controllers\Student\GpsPingController;
use App\Http\Controllers\Student\InternshipInfoController;
use App\Http\Controllers\Student\NotificationController as StudentNotificationController;
use App\Http\Controllers\Student\PersonalInfoController;
use App\Models\StudentProfile;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();

        if ($user->isPending()) {
            return redirect()->route('pending-approval');
        }

        return redirect()->route($user->homeRouteName());
    }

    return redirect()->route('login');
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
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/time', [DtrController::class, 'show'])->name('time');
    Route::post('/time/clock-in', [DtrController::class, 'clockIn'])->name('time.clock-in');
    Route::post('/time/clock-out', [DtrController::class, 'clockOut'])->name('time.clock-out');

    Route::post('/gps-pings', [GpsPingController::class, 'store'])->name('gps-pings.store');

    Route::get('/reports', [AccomplishmentReportController::class, 'index'])->name('reports');
    Route::post('/reports', [AccomplishmentReportController::class, 'store']);

    Route::get('/attendance', [DtrController::class, 'history'])->name('attendance');

    Route::get('/internship-info', [InternshipInfoController::class, 'show'])->name('internship-info');
    Route::put('/internship-info', [InternshipInfoController::class, 'update']);

    Route::get('/notifications', [StudentNotificationController::class, 'index'])->name('notifications');
    Route::post('/notifications/mark-all-read', [StudentNotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');

    Route::get('/profile', function () {
        $profile = auth()->user()->studentProfile ?? new StudentProfile;

        return view('student.profile', compact('profile'));
    })->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::put('/personal-info', [PersonalInfoController::class, 'update'])->name('personal-info.update');
});

Route::prefix('dean')->name('dean.')->middleware(['auth', 'approved', 'role:dean'])->group(function () {
    Route::get('/dashboard', [DeanDashboardController::class, 'index'])->name('dashboard');

    Route::get('/live-map', [LiveMapController::class, 'index'])->name('live-map');

    Route::get('/pending-approvals', [PendingApprovalController::class, 'index'])->name('pending-approvals');
    Route::post('/pending-approvals/{user}/approve', [PendingApprovalController::class, 'approve'])->name('pending-approvals.approve');
    Route::post('/pending-approvals/{user}/reject', [PendingApprovalController::class, 'reject'])->name('pending-approvals.reject');

    Route::get('/students', [StudentAccountController::class, 'index'])->name('students');
    Route::get('/students/create', [StudentAccountController::class, 'create'])->name('students.create');
    Route::post('/students', [StudentAccountController::class, 'store'])->name('students.store');

    Route::get('/students/{student}', [StudentProfileController::class, 'show'])->name('students.show');
    Route::get('/students/{student}/edit', [StudentProfileController::class, 'edit'])->name('students.edit');
    Route::put('/students/{student}', [StudentProfileController::class, 'update'])->name('students.update');
    Route::post('/students/{student}/reset-password', [StudentProfileController::class, 'resetPassword'])->name('students.reset-password');

    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance');

    Route::get('/reports', [DeanAccomplishmentReportController::class, 'index'])->name('reports');

    Route::get('/reports-export', [ReportExportController::class, 'index'])->name('reports-export');
    Route::get('/reports-export/download', [ReportExportController::class, 'download'])->name('reports-export.download');
    Route::get('/reports-export/download-csv', [ReportExportController::class, 'downloadCsv'])->name('reports-export.download-csv');

    Route::get('/notifications', [DeanNotificationController::class, 'index'])->name('notifications');
    Route::post('/notifications/mark-all-read', [DeanNotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');

    Route::get('/profile', function () {
        return view('dean.profile');
    })->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'approved', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/deans', [DeanAccountController::class, 'index'])->name('deans');
    Route::get('/deans/create', [DeanAccountController::class, 'create'])->name('deans.create');
    Route::post('/deans', [DeanAccountController::class, 'store'])->name('deans.store');

    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs');

    Route::get('/profile', function () {
        return view('admin.profile');
    })->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
});
