<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

/**
 * Sent to a doctor the moment a receptionist registers a patient and
 * assigns them an appointment. Delivered via two channels:
 *
 *  - database: persists in the `notifications` table so it shows up
 *    in the doctor's notification bell/list even after a refresh.
 *  - broadcast: pushed live over Pusher/Echo so the doctor's
 *    dashboard can pop up a toast with the patient's name, age, and
 *    residence *before* they walk into the room — no polling needed.
 */
class NewPatientAssigned extends Notification implements ShouldBroadcast
{
    use Queueable;

    public function __construct(public Appointment $appointment)
    {
    }

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Data persisted to the notifications table (and shown in the
     * notification bell dropdown).
     */
    public function toArray(object $notifiable): array
    {
        $patient = $this->appointment->patient;

        return [
            'appointment_id' => $this->appointment->id,
            'patient_id' => $patient->id,
            'patient_number' => $patient->patient_number,
            'patient_name' => $patient->name,
            'age' => $patient->age,
            'residence' => $patient->residence,
            'queue_number' => $this->appointment->queue_number,
            'message' => "New patient {$patient->name} (#{$patient->patient_number}) has been added to your queue.",
        ];
    }

    /**
     * The live broadcast payload. Kept identical to toArray() so the
     * front-end JS listener (resources/js/app.js) can use one shape
     * for both the toast popup and the bell list.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }

    /**
     * Broadcast privately to this doctor only.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('doctor.'.$this->appointment->doctor_id),
        ];
    }

    public function broadcastType(): string
    {
        return 'patient.assigned';
    }
}
