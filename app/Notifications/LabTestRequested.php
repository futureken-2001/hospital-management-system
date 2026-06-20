<?php

namespace App\Notifications;

use App\Models\LabTest;
use Illuminate\Bus\Queueable;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

/**
 * Sent to lab technicians the instant a doctor orders a test. Their
 * dashboard listens on the shared 'lab-technicians' channel so the
 * pending list updates live without a page refresh ("no paper
 * needed" requirement).
 */
class LabTestRequested extends Notification implements ShouldBroadcast
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
            'doctor_name' => $this->labTest->doctor->name,
            'message' => "New lab test requested: {$this->labTest->test_name} for {$patient->name}.",
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }

    /**
     * Broadcast on a shared channel since any lab_technician on duty
     * should see new pending tests instantly.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('lab-technicians'),
        ];
    }

    public function broadcastType(): string
    {
        return 'labtest.requested';
    }
}
