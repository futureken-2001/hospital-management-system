<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentQueueTest extends TestCase
{
    use RefreshDatabase;

    public function test_queue_number_increments_per_doctor_per_day(): void
    {
        $doctor = User::factory()->doctor()->create();
        $patientOne = Patient::factory()->create();
        $patientTwo = Patient::factory()->create();

        $first = Appointment::create([
            'patient_id' => $patientOne->id,
            'doctor_id' => $doctor->id,
            'appointment_date' => now()->toDateString(),
        ]);

        $second = Appointment::create([
            'patient_id' => $patientTwo->id,
            'doctor_id' => $doctor->id,
            'appointment_date' => now()->toDateString(),
        ]);

        $this->assertSame(1, $first->queue_number);
        $this->assertSame(2, $second->queue_number);
    }

    public function test_queue_number_resets_for_a_new_day(): void
    {
        $doctor = User::factory()->doctor()->create();
        $patientOne = Patient::factory()->create();
        $patientTwo = Patient::factory()->create();

        $yesterday = Appointment::create([
            'patient_id' => $patientOne->id,
            'doctor_id' => $doctor->id,
            'appointment_date' => now()->subDay()->toDateString(),
        ]);

        $today = Appointment::create([
            'patient_id' => $patientTwo->id,
            'doctor_id' => $doctor->id,
            'appointment_date' => now()->toDateString(),
        ]);

        $this->assertSame(1, $yesterday->queue_number);
        $this->assertSame(1, $today->queue_number);
    }

    public function test_queue_numbers_are_independent_per_doctor(): void
    {
        $doctorOne = User::factory()->doctor()->create();
        $doctorTwo = User::factory()->doctor()->create();
        $patient = Patient::factory()->create();

        $appointmentWithDoctorOne = Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctorOne->id,
            'appointment_date' => now()->toDateString(),
        ]);

        $appointmentWithDoctorTwo = Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctorTwo->id,
            'appointment_date' => now()->toDateString(),
        ]);

        $this->assertSame(1, $appointmentWithDoctorOne->queue_number);
        $this->assertSame(1, $appointmentWithDoctorTwo->queue_number);
    }

    public function test_doctor_can_only_advance_their_own_queue(): void
    {
        $doctorOne = User::factory()->doctor()->create();
        $doctorTwo = User::factory()->doctor()->create();
        $patient = Patient::factory()->create();

        $appointment = Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctorOne->id,
            'appointment_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($doctorTwo)->patch(
            route('appointments.update-status', $appointment),
            ['status' => 'called']
        );

        $response->assertForbidden();
    }
}
