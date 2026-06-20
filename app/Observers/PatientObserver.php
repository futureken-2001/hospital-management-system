<?php

namespace App\Observers;

use App\Models\AuditLog;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Generates the patient_number (format P-0001, P-0002, ...) and
 * writes audit log entries for every patient create/update/delete.
 *
 * The number is derived from the highest numeric suffix that has
 * ever been issued, NOT from the row count — so it keeps climbing
 * even if a patient is later deleted, and it never reuses a number
 * (requirement: "auto-increment, never resets").
 */
class PatientObserver
{
    public function creating(Patient $patient): void
    {
        $patient->patient_number = $this->nextPatientNumber();

        if (Auth::check()) {
            $patient->created_by = Auth::id();
            $patient->updated_by = Auth::id();
        }
    }

    public function created(Patient $patient): void
    {
        AuditLog::record('created', $patient, old: null, new: $patient->only([
            'patient_number', 'name', 'age', 'residence', 'phone',
        ]));
    }

    public function updating(Patient $patient): void
    {
        if (Auth::check()) {
            $patient->updated_by = Auth::id();
        }
    }

    public function updated(Patient $patient): void
    {
        AuditLog::record(
            'updated',
            $patient,
            $patient->getOriginal(),
            $patient->getChanges()
        );
    }

    public function deleted(Patient $patient): void
    {
        AuditLog::record('deleted', $patient, old: $patient->only([
            'patient_number', 'name', 'age', 'residence', 'phone',
        ]), new: null);
    }

    /**
     * Lock the table for the duration of the read+increment so two
     * receptionists registering patients at the exact same moment
     * can never be handed the same number.
     */
    protected function nextPatientNumber(): string
    {
        return DB::transaction(function () {
            $lastNumber = Patient::lockForUpdate()
                ->selectRaw("MAX(CAST(SUBSTRING(patient_number, 3) AS UNSIGNED)) as max_number")
                ->value('max_number');

            $next = ((int) $lastNumber) + 1;

            return 'P-'.str_pad((string) $next, 4, '0', STR_PAD_LEFT);
        });
    }
}
