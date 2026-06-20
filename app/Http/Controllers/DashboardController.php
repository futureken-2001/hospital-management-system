<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\LabTest;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * One dashboard route, four different views depending on role. Each
 * view gets its own lightweight stats so the page loads fast (no
 * heavy reporting queries on every login).
 */
class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        return match ($user->role) {
            'super_admin' => $this->adminDashboard(),
            'doctor' => $this->doctorDashboard($user->id),
            'lab_technician' => $this->labTechnicianDashboard(),
            'receptionist' => $this->receptionistDashboard(),
            default => view('dashboard.index'),
        };
    }

    protected function adminDashboard(): View
    {
        $stats = [
            'total_patients' => Patient::count(),
            'total_doctors' => \App\Models\User::where('role', 'doctor')->count(),
            'appointments_today' => Appointment::whereDate('appointment_date', now())->count(),
            'pending_lab_tests' => LabTest::pending()->count(),
            'completed_lab_tests' => LabTest::completed()->count(),
        ];

        return view('dashboard.admin', compact('stats'));
    }

    protected function doctorDashboard(int $doctorId): View
    {
        $queue = Appointment::with('patient')
            ->forDoctorToday($doctorId)
            ->orderBy('queue_number')
            ->get();

        $stats = [
            'waiting' => $queue->where('status', 'waiting')->count(),
            'called' => $queue->where('status', 'called')->count(),
            'done' => $queue->where('status', 'done')->count(),
            'pending_lab_tests' => LabTest::where('doctor_id', $doctorId)->notCompleted()->count(),
        ];

        return view('dashboard.doctor', compact('queue', 'stats'));
    }

    protected function labTechnicianDashboard(): View
    {
        $tests = LabTest::with(['patient', 'doctor'])
            ->notCompleted()
            ->orderBy('requested_at')
            ->get();

        $stats = [
            'pending' => $tests->where('status', 'pending')->count(),
            'in_progress' => $tests->where('status', 'in_progress')->count(),
            'completed_today' => LabTest::completed()->whereDate('completed_at', now())->count(),
        ];

        return view('dashboard.lab_technician', compact('tests', 'stats'));
    }

    protected function receptionistDashboard(): View
    {
        $todaysQueue = Appointment::with(['patient', 'doctor'])
            ->whereDate('appointment_date', now())
            ->orderBy('doctor_id')
            ->orderBy('queue_number')
            ->get();

        $stats = [
            'patients_registered_today' => Patient::whereDate('created_at', now())->count(),
            'appointments_today' => $todaysQueue->count(),
            'waiting_now' => $todaysQueue->where('status', 'waiting')->count(),
        ];

        return view('dashboard.receptionist', compact('todaysQueue', 'stats'));
    }
}
