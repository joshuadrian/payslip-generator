<?php

namespace Database\Seeders;

use App\Models\AttendancePeriod;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        activity()->disableLogging();

        $this->call([RolePermissionSeeder::class, SettingSeeder::class]);

        AttendancePeriod::create([
            'start_date' => now()->startOfMonth(),
            'end_date' => now()->endOfMonth(),
        ]);

        User::factory(1)
            ->admin()
            ->create();

        User::factory(100)
            ->hasSalaries(1)
            ->employee()
            ->create();

        activity()->enableLogging();
    }
}
