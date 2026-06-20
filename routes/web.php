<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LabTestController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => redirect()->route('login'));

/*
|--------------------------------------------------------------------------
| Authenticated routes (any role)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Notification bell — available to every authenticated role.
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');

    // Breeze profile (every role can manage their own account).
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |----------------------------------------------------------------------
    | Patients module
    | Full CRUD: receptionist + super_admin.
    | Read-only: doctor + lab_technician (full patient record visibility).
    |----------------------------------------------------------------------
    */
    Route::middleware('role:receptionist,super_admin,doctor,lab_technician')->group(function () {
        Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');
        Route::get('/patients/{patient}', [PatientController::class, 'show'])->name('patients.show');
        Route::get('/patients/{patient}/print-card', [PatientController::class, 'printCard'])->name('patients.print-card');
    });

    Route::middleware('role:receptionist,super_admin')->group(function () {
        Route::get('/patients-create', [PatientController::class, 'create'])->name('patients.create');
        Route::post('/patients', [PatientController::class, 'store'])->name('patients.store');
        Route::get('/patients/{patient}/edit', [PatientController::class, 'edit'])->name('patients.edit');
        Route::put('/patients/{patient}', [PatientController::class, 'update'])->name('patients.update');
        Route::delete('/patients/{patient}', [PatientController::class, 'destroy'])->name('patients.destroy');
    });

    /*
    |----------------------------------------------------------------------
    | Appointments / daily queue
    | Create + manage queue: receptionist + super_admin.
    | Advance status (call/done): doctor + super_admin.
    |----------------------------------------------------------------------
    */
    Route::middleware('role:receptionist,super_admin,doctor')->group(function () {
        Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    });

    Route::middleware('role:receptionist,super_admin')->group(function () {
        Route::get('/appointments-create', [AppointmentController::class, 'create'])->name('appointments.create');
        Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
        Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');
    });

    Route::middleware('role:doctor,super_admin')->group(function () {
        Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.update-status');
    });

    /*
    |----------------------------------------------------------------------
    | Lab Tests module
    | Doctors/super_admin assign tests. Lab technicians fulfill them.
    |----------------------------------------------------------------------
    */
    Route::middleware('role:doctor,super_admin,lab_technician')->group(function () {
        Route::get('/lab-tests', [LabTestController::class, 'index'])->name('lab-tests.index');
        Route::get('/lab-tests/{labTest}', [LabTestController::class, 'show'])->name('lab-tests.show');
        Route::get('/lab-tests/{labTest}/print', [LabTestController::class, 'printRequest'])->name('lab-tests.print');
    });

    Route::middleware('role:doctor,super_admin')->group(function () {
        Route::get('/lab-tests-create', [LabTestController::class, 'create'])->name('lab-tests.create');
        Route::post('/lab-tests', [LabTestController::class, 'store'])->name('lab-tests.store');
    });

    Route::middleware('role:lab_technician,super_admin')->group(function () {
        Route::get('/lab-tests/{labTest}/edit', [LabTestController::class, 'edit'])->name('lab-tests.edit');
        Route::put('/lab-tests/{labTest}', [LabTestController::class, 'update'])->name('lab-tests.update');
    });

    /*
    |----------------------------------------------------------------------
    | Staff (doctors / lab technicians / receptionists) management
    | super_admin only.
    |----------------------------------------------------------------------
    */
    Route::middleware('role:super_admin')->prefix('staff')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/', [UserController::class, 'store'])->name('users.store');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });
});

/*
|--------------------------------------------------------------------------
| Breeze authentication routes (guest + auth split, registration
| intentionally omitted — staff accounts are created by super_admin
| via the Staff module above, not by public sign-up).
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
