<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'name' => 'shift_start',
                'value' => '09:00',
                'type' => 'time'
            ],
            [
                'name' => 'shift_end',
                'value' => '17:00',
                'type' => 'time'
            ],
            [
                'name' => 'overtime_multiplier',
                'value' => '2',
                'type' => 'float'
            ],
            [
                'name' => 'max_overtime_duration',
                'value' => '3',
                'type' => 'float'
            ],
        ];

        array_walk($settings, fn ($d) => Setting::create($d));
    }
}
