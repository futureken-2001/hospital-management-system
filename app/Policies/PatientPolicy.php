<?php

namespace App\Policies;

use App\Models\Patient;
use App\Models\User;

/**
 * Patients module rules:
 *  - receptionists register and manage daily queue/patient records.
 *  - doctors/super_admins can view ALL patient records (full
 *    clinical access) but should not casually delete registration
 *    data, so delete is reserved for receptionists + super_admin.
 *  - lab_technicians only need read access to know who a sample
 *    belongs to; they never create/edit patients directly.
 */
class PatientPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['receptionist', 'doctor', 'super_admin', 'lab_technician'], true);
    }

    public function view(User $user, Patient $patient): bool
    {
        return in_array($user->role, ['receptionist', 'doctor', 'super_admin', 'lab_technician'], true);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['receptionist', 'super_admin'], true);
    }

    public function update(User $user, Patient $patient): bool
    {
        return in_array($user->role, ['receptionist', 'super_admin'], true);
    }

    public function delete(User $user, Patient $patient): bool
    {
        return in_array($user->role, ['receptionist', 'super_admin'], true);
    }
}
