<?php

namespace Database\Factories;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LabTest>
 */
class LabTestFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'patient_id' => Patient::factory(),
            'doctor_id' => User::factory()->doctor(),
            'test_name' => fake()->randomElement([
                'Full Blood Count',
                'Malaria Test',
                'Urinalysis',
                'Blood Sugar (RBS)',
                'HIV Test',
                'Widal Test',
                'Stool Examination',
                'Liver Function Test',
            ]),
            'status' => 'pending',
            'requested_at' => now(),
        ];
    }

    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'in_progress',
            'lab_technician_id' => User::factory()->labTechnician(),
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'lab_technician_id' => User::factory()->labTechnician(),
            'result' => fake()->sentence(10),
            'completed_at' => now(),
        ]);
    }
}
