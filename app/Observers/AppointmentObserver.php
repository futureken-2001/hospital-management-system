<?php

namespace App\Observers;

use App\Models\Appointment;
use App\Notifications\NewPatientAssigned;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Computes the per-doctor, per-day queue_number and fires the
 * real-time "new patient assigned" notification to the doctor.
 */
class AppointmentObserver
{
    public function creating(Appointment $appointment): void
    {
        $appointment->queue_number = $this->nextQueueNumber(
            $appointment->doctor_id,
            $appointment->appointment_date ?? now()->toDateString()
        );

        if (Auth::check() && empty($appointment->created_by)) {
            $appointment->created_by = Auth::id();
        }
    }

    public function created(Appointment $appointment): void
    {
        $doctor = $appointment->doctor;

        if ($doctor) {
            $doctor->notify(new NewPatientAssigned($appointment));
        }
    }

    /**
     * queue_number resets to 1 for a doctor as soon as the date
     * changes, because the lookup is scoped to
     * (doctor_id, appointment_date) rather than being a global
     * auto-increment. Locked for update to avoid two receptionists
     * generating the same queue position simultaneously.
     */
    protected function nextQueueNumber(int $doctorId, string $date): int
    {
        return DB::transaction(function () use ($doctorId, $date) {
            $last = Appointment::where('doctor_id', $doctorId)
                ->whereDate('appointment_date', $date)
                ->lockForUpdate()
                ->max('queue_number');

            return ((int) $last) + 1;
        });
    }
}
