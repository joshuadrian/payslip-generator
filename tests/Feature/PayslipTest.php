<?php

use App\Models\User;
use App\Models\Payroll;
use App\Models\AttendancePeriod;
use App\Services\PayslipService;
use Database\Seeders\UserDataSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('running payslip service', function () {
    $this->seed();
    $this->seed(UserDataSeeder::class);

    $period = AttendancePeriod::find(1);
    $payroll = Payroll::create(['attendance_period_id' => $period->id]);
    $user = User::employee()
        ->with([
            'latestSalary',
            'totalReimbursement',
            'totalOvertime',
            'attendances' => fn($q) => $q->whereBetween('date', [
                $period->start_date->startOfDay(),
                $period->end_date->endOfDay()
            ])
        ])
        ->first();

    $service = new PayslipService;
    $result = $service->run($payroll, $user, $period);
    expect($result)->toBeTrue();
});
