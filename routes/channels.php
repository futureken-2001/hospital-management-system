<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| These authorize the PrivateChannel()s referenced in
| App\Notifications\NewPatientAssigned, LabTestRequested, and
| LabTestCompleted. A user may only listen on a channel they're
| actually allowed to receive — a doctor only their own channel, any
| lab_technician (or super_admin) the shared lab channel.
|
*/

// "doctor.{doctorId}" — only that specific doctor (or a super_admin
// impersonating/monitoring) may listen for their own patient/lab
// notifications.
Broadcast::channel('doctor.{doctorId}', function ($user, $doctorId) {
    return $user->isSuperAdmin() || (int) $user->id === (int) $doctorId;
});

// "lab-technicians" — shared by every lab_technician on duty plus
// super_admin, since any of them should see new pending tests the
// instant a doctor orders one.
Broadcast::channel('lab-technicians', function ($user) {
    return $user->isLabTechnician() || $user->isSuperAdmin();
});
