<?php

namespace Tests\Feature;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_patient_number_is_generated_in_sequence(): void
    {
        $receptionist = User::factory()->receptionist()->create();
        $this->actingAs($receptionist);

        $first = Patient::create([
            'name' => 'Jane Doe',
            'age' => 30,
            'residence' => 'Kampala',
            'phone' => '0700000001',
        ]);

        $second = Patient::create([
            'name' => 'John Smith',
            'age' => 45,
            'residence' => 'Entebbe',
            'phone' => '0700000002',
        ]);

        $this->assertSame('P-0001', $first->patient_number);
        $this->assertSame('P-0002', $second->patient_number);
    }

    public function test_patient_number_never_reuses_a_deleted_number(): void
    {
        $receptionist = User::factory()->receptionist()->create();
        $this->actingAs($receptionist);

        $first = Patient::create([
            'name' => 'Jane Doe', 'age' => 30, 'residence' => 'Kampala', 'phone' => '0700000001',
        ]);
        $first->delete();

        $second = Patient::create([
            'name' => 'John Smith', 'age' => 45, 'residence' => 'Entebbe', 'phone' => '0700000002',
        ]);

        $this->assertSame('P-0001', $first->patient_number);
        $this->assertSame('P-0002', $second->patient_number);
    }

    public function test_receptionist_can_register_a_patient_via_http(): void
    {
        $receptionist = User::factory()->receptionist()->create();

        $response = $this->actingAs($receptionist)->post(route('patients.store'), [
            'name' => 'Jane Doe',
            'age' => 30,
            'residence' => 'Kampala',
            'phone' => '0700000001',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('patients', ['name' => 'Jane Doe', 'patient_number' => 'P-0001']);
    }

    public function test_lab_technician_cannot_register_a_patient(): void
    {
        $labTechnician = User::factory()->labTechnician()->create();

        $response = $this->actingAs($labTechnician)->post(route('patients.store'), [
            'name' => 'Jane Doe',
            'age' => 30,
            'residence' => 'Kampala',
            'phone' => '0700000001',
        ]);

        $response->assertForbidden();
    }

    public function test_patient_search_scope_matches_by_number_name_or_phone(): void
    {
        $receptionist = User::factory()->receptionist()->create();
        $this->actingAs($receptionist);

        Patient::create(['name' => 'Jane Doe', 'age' => 30, 'residence' => 'Kampala', 'phone' => '0700000001']);
        Patient::create(['name' => 'John Smith', 'age' => 45, 'residence' => 'Entebbe', 'phone' => '0700000002']);

        $this->assertCount(1, Patient::search('Jane')->get());
        $this->assertCount(1, Patient::search('P-0002')->get());
        $this->assertCount(2, Patient::search(null)->get());
    }
}
