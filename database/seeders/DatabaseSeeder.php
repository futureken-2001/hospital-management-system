<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run with: php artisan migrate --seed
     * Or standalone with: php artisan db:seed
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            DemoDataSeeder::class,
        ]);
    }
}
