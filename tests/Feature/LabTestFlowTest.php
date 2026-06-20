<?php

namespace Tests\Feature;

use App\Models\LabTest;
use App\Models\Patient;
use App\Models\User;
use App\Notifications\LabTestCompleted;
use App\Notifications\LabTestRequested;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class LabTestFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_creating_a_lab_test_notifies_every_lab_technician(): void
    {
        Notification::fake();

        $doctor = User::factory()->doctor()->create();
        $labTechOne = User::factory()->labTechnician()->create();
        $labTechTwo = User::factory()->labTechnician()->create();
        $patient = Patient::factory()->create();

        $labTest = LabTest::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'test_name' => 'Full Blood Count',
        ]);

        $this->assertSame('pending', $labTest->status);

        Notification::assertSentTo($labTechOne, LabTestRequested::class);
        Notification::assertSentTo($labTechTwo, LabTestRequested::class);
    }

    public function test_completing_a_lab_test_notifies_the_requesting_doctor(): void
    {
        Notification::fake();

        $doctor = User::factory()->doctor()->create();
        $labTechnician = User::factory()->labTechnician()->create();
        $patient = Patient::factory()->create();

        $labTest = LabTest::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'test_name' => 'Malaria Test',
        ]);

        $this->actingAs($labTechnician)->put(route('lab-tests.update', $labTest), [
            'status' => 'completed',
            'result' => 'Negative',
        ]);

        $labTest->refresh();

        $this->assertSame('completed', $labTest->status);
        $this->assertSame('Negative', $labTest->result);
        $this->assertNotNull($labTest->completed_at);

        Notification::assertSentTo($doctor, LabTestCompleted::class);
    }

    public function test_doctor_cannot_update_a_lab_result(): void
    {
        $doctor = User::factory()->doctor()->create();
        $patient = Patient::factory()->create();

        $labTest = LabTest::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'test_name' => 'Urinalysis',
        ]);

        $response = $this->actingAs($doctor)->put(route('lab-tests.update', $labTest), [
            'status' => 'completed',
            'result' => 'Should not be allowed',
        ]);

        $response->assertForbidden();
    }
}
