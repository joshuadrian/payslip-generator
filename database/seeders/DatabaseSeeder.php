<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use Carbon\CarbonPeriod;
use Illuminate\Database\Seeder;
use App\Models\AttendancePeriod;
use Illuminate\Database\Eloquent\Collection;

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

        /**
         * @var Collection $users
         */
        $users = User::factory(100)
            ->hasSalaries(1)
            ->employee()
            ->create();

        $periods = CarbonPeriod::create(now()->startOfMonth(), now()->endOfMonth());

        static::addAttendances($users, $periods);
        static::addReimbursementsAndOvertimes($users->take(5), $periods);

        activity()->enableLogging();
    }

    public static function addAttendances(Collection $users, CarbonPeriod $periods)
    {
        foreach ($periods as $value) {
            $dateStr = $value->format('Y-m-d');
            $checkInAt = Carbon::parse($dateStr)->setHour(9);
            $checkOutAt = Carbon::parse($dateStr)->setHour(17);
            foreach ($users as $user) {
                $user->attendances()->create([
                    'date' => $dateStr,
                    'attendance_period_id' => 1,
                    'checked_in_at' => $checkInAt,
                    'checked_out_at' => $checkOutAt
                ]);
            }
        }
    }

    public static function addReimbursementsAndOvertimes(Collection $users, CarbonPeriod $periods)
    {
        foreach ($periods as $value) {
            $dateStr = $value->format('Y-m-d');

            foreach ($users as $user) {
                for ($i = 0; $i < rand(0, 3); $i++) {
                    $user->reimbursements()->create([
                        'date' => $dateStr,
                        'amount' => rand(1, 5) * 100000,
                        'description' => fake()->sentence(3),
                    ]);
                }

                if (rand(0, 1) === 1) {
                    $user->overtimes()->create([
                        'date' => $dateStr,
                        'duration_hours' => rand(1, 3),
                    ]);
                }
            }
        }
    }
}
