<?php

namespace App\Observers;

use App\Models\LabTest;
use App\Models\User;
use App\Notifications\LabTestCompleted;
use App\Notifications\LabTestRequested;

/**
 * Fires the two notification events in the lab flow:
 *   1. created()  -> notify all lab_technicians that a new pending
 *      test exists (replaces the paper request form).
 *   2. updated()  -> if the status just flipped to 'completed',
 *      notify the requesting doctor that the result is ready.
 */
class LabTestObserver
{
    public function creating(LabTest $labTest): void
    {
        $labTest->requested_at = $labTest->requested_at ?? now();
        $labTest->status = $labTest->status ?: 'pending';
    }

    public function created(LabTest $labTest): void
    {
        // Notify every lab_technician so whoever is on duty sees it
        // appear instantly on their dashboard.
        $technicians = User::where('role', 'lab_technician')->get();

        foreach ($technicians as $technician) {
            $technician->notify(new LabTestRequested($labTest));
        }
    }

    public function updated(LabTest $labTest): void
    {
        if ($labTest->wasChanged('status') && $labTest->status === 'completed') {
            $labTest->doctor?->notify(new LabTestCompleted($labTest));
        }
    }
}
