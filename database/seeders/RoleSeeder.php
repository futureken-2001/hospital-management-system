<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Creates the four Spatie roles, one per value of the
     * users.role enum. Permissions aren't broken out individually
     * in this build (authorization lives in the Policies), but the
     * roles themselves are required for Gate::allows()/can() role
     * checks and so they show up correctly in `php artisan permission:show`.
     */
    public function run(): void
    {
        $roles = ['super_admin', 'doctor', 'lab_technician', 'receptionist'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }
    }
}
