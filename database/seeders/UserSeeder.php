<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Demo login credentials (also documented in README.md):
     *
     *   super_admin     admin@hms.test       password
     *   doctor          dr.amara@hms.test    password
     *   doctor          dr.kato@hms.test     password
     *   lab_technician  lab@hms.test         password
     *   receptionist    frontdesk@hms.test   password
     *
     * Two doctors are seeded (not just one) so the appointments/
     * queue screens have more than one column to demonstrate the
     * per-doctor daily queue reset.
     */
    public function run(): void
    {
        $accounts = [
            [
                'name' => 'System Administrator',
                'email' => 'admin@hms.test',
                'role' => 'super_admin',
            ],
            [
                'name' => 'Dr. Amara Whitfield',
                'email' => 'dr.amara@hms.test',
                'role' => 'doctor',
            ],
            [
                'name' => 'Dr. Brian Kato',
                'email' => 'dr.kato@hms.test',
                'role' => 'doctor',
            ],
            [
                'name' => 'Lab Technician',
                'email' => 'lab@hms.test',
                'role' => 'lab_technician',
            ],
            [
                'name' => 'Front Desk Receptionist',
                'email' => 'frontdesk@hms.test',
                'role' => 'receptionist',
            ],
        ];

        foreach ($accounts as $account) {
            $user = User::firstOrCreate(
                ['email' => $account['email']],
                [
                    'name' => $account['name'],
                    'role' => $account['role'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );

            // Keep the Spatie role pivot in sync with the role enum,
            // same as UserController::store()/update() do at runtime.
            $user->syncRoles([$account['role']]);
        }
    }
}
