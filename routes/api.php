<?php

use App\Models\Appointment;
use App\Models\LabTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| These are intentionally small, read-only, polling-friendly endpoints.
| They exist so the doctor queue board and lab technician dashboard
| can refresh themselves every few seconds even when Pusher broadcast
| credentials are not configured (see .env.example PUSHER_* keys).
| Since this is a server-rendered Blade app (not a SPA), these reuse
| the same session-based "auth" guard as routes/web.php — no separate
| API tokens needed for the front-end JS fetch() calls.
|
*/

Route::middleware('auth')->group(function () {

    // Doctor's live queue for today.
    Route::get('/queue/{doctor}', function (Request $request, int $doctor) {
        abort_unless(
            $request->user()->isSuperAdmin() || $request->user()->id === $doctor,
            403
        );

        return Appointment::with('patient')
            ->where('doctor_id', $doctor)
            ->whereDate('appointment_date', now())
            ->orderBy('queue_number')
            ->get();
    })->name('api.queue.show');

    // Lab technician's live pending/in-progress test list.
    Route::get('/lab-tests/pending', function (Request $request) {
        abort_unless(
            in_array($request->user()->role, ['lab_technician', 'super_admin'], true),
            403
        );

        return LabTest::with(['patient', 'doctor'])
            ->where('status', '!=', 'completed')
            ->orderBy('requested_at')
            ->get();
    })->name('api.lab-tests.pending');

    // Current user's unread notification count, for the navbar badge.
    Route::get('/notifications/unread-count', function (Request $request) {
        return ['count' => $request->user()->unreadNotifications()->count()];
    })->name('api.notifications.unread-count');
});
