<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Seeder;

class UserDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        activity()->disableLogging();

        $users = User::employee()->get();

        $periods = CarbonPeriod::create(now()->startOfMonth(), now()->endOfMonth())->filter(fn($date) => !$date->isWeekend());

        foreach ($periods as $date) {
            $dateStr = $date->format('Y-m-d');
            $checkInAt = Carbon::parse($dateStr)->setHour(9);
            $checkOutAt = Carbon::parse($dateStr)->setHour(17);
            $haveAbsences = rand(0, 1);
            foreach ($users as $user) {
                if (rand(0, 1) === 1 || !$haveAbsences) {
                    $user->attendances()->create([
                        'date' => $dateStr,
                        'attendance_period_id' => 1,
                        'checked_in_at' => $checkInAt,
                        'checked_out_at' => $checkOutAt
                    ]);
                }

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

        activity()->enableLogging();
    }
}
