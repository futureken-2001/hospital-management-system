<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;

/**
 * Appointments / queue rules:
 *  - receptionists create appointments (assign patient -> doctor)
 *    and manage the daily queue.
 *  - doctors/super_admins can update status (waiting -> called ->
 *    done) for appointments, since they're the ones running the
 *    queue from the consultation room. A doctor may only advance
 *    their OWN queue; super_admin can touch any.
 */
class AppointmentPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['receptionist', 'doctor', 'super_admin'], true);
    }

    public function view(User $user, Appointment $appointment): bool
    {
        if ($user->isSuperAdmin() || $user->isReceptionist()) {
            return true;
        }

        return $user->isDoctor() && $appointment->doctor_id === $user->id;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['receptionist', 'super_admin'], true);
    }

    public function updateStatus(User $user, Appointment $appointment): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->isDoctor() && $appointment->doctor_id === $user->id;
    }

    public function delete(User $user, Appointment $appointment): bool
    {
        return in_array($user->role, ['receptionist', 'super_admin'], true);
    }
}
