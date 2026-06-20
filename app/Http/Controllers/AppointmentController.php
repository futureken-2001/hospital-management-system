<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentStatusRequest;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    /**
     * Receptionist's daily queue board: every appointment for today,
     * grouped implicitly by doctor via the view.
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Appointment::class);

        $date = $request->query('date', now()->toDateString());

        $appointments = Appointment::with(['patient', 'doctor'])
            ->whereDate('appointment_date', $date)
            ->orderBy('doctor_id')
            ->orderBy('queue_number')
            ->get()
            ->groupBy('doctor_id');

        $doctors = User::where('role', 'doctor')->orderBy('name')->get();

        return view('appointments.index', compact('appointments', 'doctors', 'date'));
    }

    public function create(Request $request): View
    {
        $this->authorize('create', Appointment::class);

        $doctors = User::where('role', 'doctor')->orderBy('name')->get();
        $patientId = $request->query('patient_id');

        return view('appointments.create', compact('doctors', 'patientId'));
    }

    /**
     * Assign a registered patient to a doctor's queue. queue_number
     * is computed automatically by AppointmentObserver; the doctor
     * is notified instantly (database + broadcast).
     */
    public function store(StoreAppointmentRequest $request): RedirectResponse
    {
        $appointment = Appointment::create($request->validated());

        return redirect()
            ->route('appointments.index')
            ->with('status', "Patient added to Dr. {$appointment->doctor->name}'s queue as #{$appointment->queue_number}.");
    }

    /**
     * Advance (or otherwise change) an appointment's status. This is
     * the endpoint the doctor's "Call next patient" / "Mark done"
     * buttons hit.
     */
    public function updateStatus(UpdateAppointmentStatusRequest $request, Appointment $appointment): RedirectResponse
    {
        $appointment->update(['status' => $request->validated('status')]);

        return back()->with('status', 'Queue status updated.');
    }

    public function destroy(Appointment $appointment): RedirectResponse
    {
        $this->authorize('delete', $appointment);

        $appointment->delete();

        return back()->with('status', 'Appointment removed from queue.');
    }
}
