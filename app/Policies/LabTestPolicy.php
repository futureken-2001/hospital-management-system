<?php

namespace App\Policies;

use App\Models\LabTest;
use App\Models\User;

/**
 * Lab Test module rules:
 *  - doctors/super_admins assign (create) lab tests.
 *  - lab_technicians update status + enter results, but never
 *    create tests themselves (they only fulfill what a doctor
 *    ordered).
 */
class LabTestPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['doctor', 'super_admin', 'lab_technician'], true);
    }

    public function view(User $user, LabTest $labTest): bool
    {
        if ($user->isSuperAdmin() || $user->isLabTechnician()) {
            return true;
        }

        return $user->isDoctor() && $labTest->doctor_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->hasFullClinicalAccess();
    }

    public function updateResult(User $user, LabTest $labTest): bool
    {
        return $user->isLabTechnician() || $user->isSuperAdmin();
    }

    public function delete(User $user, LabTest $labTest): bool
    {
        return $user->isSuperAdmin() || ($user->isDoctor() && $labTest->doctor_id === $user->id);
    }
}
