<?php

namespace Database\Seeders;

use App\Models\LabTest;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Generates ~20 patients with realistic appointments (assigned to the
 * two real seeded doctors) and a handful of lab tests in different
 * stages (pending / in_progress / completed), so the dashboards and
 * queue boards have something meaningful to show immediately after
 * `php artisan migrate --seed` instead of an empty screen.
 */
class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $receptionist = User::where('role', 'receptionist')->first();
        $doctors = User::where('role', 'doctor')->get();
        $labTechnician = User::where('role', 'lab_technician')->first();

        if ($doctors->isEmpty() || ! $receptionist) {
            // UserSeeder hasn't run yet — nothing sensible to attach
            // these demo records to, so bail out quietly.
            return;
        }

        $patients = Patient::factory()
            ->count(20)
            ->create(['created_by' => $receptionist->id, 'updated_by' => $receptionist->id]);

        foreach ($patients as $index => $patient) {
            $doctor = $doctors[$index % $doctors->count()];

            // Spread today's queue across waiting / called / done so
            // the doctor dashboard demonstrates all three states.
            $status = match (true) {
                $index % 5 === 0 => 'done',
                $index % 3 === 0 => 'called',
                default => 'waiting',
            };

            $appointment = $patient->appointments()->create([
                'doctor_id' => $doctor->id,
                'status' => $status,
                'appointment_date' => now()->toDateString(),
                'created_by' => $receptionist->id,
            ]);

            // Give roughly a third of patients a lab test, in a mix
            // of stages, so the lab_technician dashboard isn't empty.
            if ($index % 3 === 0 && $labTechnician) {
                $labStatus = match (true) {
                    $index % 9 === 0 => 'completed',
                    $index % 6 === 0 => 'in_progress',
                    default => 'pending',
                };

                $labTest = LabTest::factory()
                    ->for($patient)
                    ->state([
                        'doctor_id' => $doctor->id,
                        'status' => $labStatus,
                    ])
                    ->make();

                if ($labStatus !== 'pending') {
                    $labTest->lab_technician_id = $labTechnician->id;
                }
                if ($labStatus === 'completed') {
                    $labTest->result = 'Within normal range.';
                    $labTest->completed_at = now();
                }

                $labTest->save();
            }

            unset($appointment);
        }
    }
}
