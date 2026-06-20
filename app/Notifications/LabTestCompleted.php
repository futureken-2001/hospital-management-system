<?php

namespace App\Notifications;

use App\Models\LabTest;
use Illuminate\Bus\Queueable;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

/**
 * Sent to the requesting doctor as soon as a lab_technician saves a
 * result and marks the test completed.
 */
class LabTestCompleted extends Notification implements ShouldBroadcast
{
    use Queueable;

    public function __construct(public LabTest $labTest)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toArray(object $notifiable): array
    {
        $patient = $this->labTest->patient;

        return [
            'lab_test_id' => $this->labTest->id,
            'patient_id' => $patient->id,
            'patient_number' => $patient->patient_number,
            'patient_name' => $patient->name,
            'test_name' => $this->labTest->test_name,
            'message' => "Lab result ready: {$this->labTest->test_name} for {$patient->name}.",
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('doctor.'.$this->labTest->doctor_id),
        ];
    }

    public function broadcastType(): string
    {
        return 'labtest.completed';
    }
}
