<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([RolePermissionSeeder::class, SettingSeeder::class]);

        User::factory(1)
            ->admin()
            ->create();

        User::factory(100)
            ->hasSalaries(1)
            ->employee()
            ->create();
    }
}
