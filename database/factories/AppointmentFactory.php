<?php

namespace Database\Factories;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 *
 * queue_number is intentionally NOT set here — AppointmentObserver
 * computes it automatically on creating(), the same as a real
 * receptionist-submitted assignment would.
 */
class AppointmentFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'patient_id' => Patient::factory(),
            'doctor_id' => User::factory()->doctor(),
            'status' => 'waiting',
            'appointment_date' => now()->toDateString(),
        ];
    }

    public function called(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'called']);
    }

    public function done(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'done']);
    }
}
