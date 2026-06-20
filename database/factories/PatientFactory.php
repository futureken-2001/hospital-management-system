<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 *
 * patient_number is intentionally NOT set here — PatientObserver
 * generates it automatically on creating(), exactly the same way it
 * would for a real receptionist-submitted form.
 */
class PatientFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'age' => fake()->numberBetween(1, 95),
            'residence' => fake()->city(),
            'phone' => fake()->numerify('07########'),
        ];
    }
}
