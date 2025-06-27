<?php

namespace App\Services;

use App\Models\AttendancePeriod;
use App\Models\Payroll;
use App\Models\Setting;
use App\Models\User;
use Carbon\CarbonPeriod;

class PayslipService
{
    public function run(Payroll $payroll, User $user, AttendancePeriod $attPeriod)
    {
        $date = now();

        $baseSalary = $user->latestSalary->amount ?? 0;
        $periods = CarbonPeriod::create($attPeriod->start_date, $attPeriod->end_date)->filter(fn($date) => !$date->isWeekend());
        $totalWorkingDays = $periods->count();
        $totalAbsence = $totalWorkingDays - count($user->attendances);

        $settings = Setting::whereIn('name', ['shift_start', 'shift_end'])->pluck('value', 'name');
        $shiftStart = $settings['shift_start'] ?? '08:00';
        $shiftEnd = $settings['shift_end'] ?? '17:00';

        $shiftStart = $date->copy()->setTimeFromTimeString($shiftStart);
        $shiftEnd = $date->copy()->setTimeFromTimeString($shiftEnd);

        $workingHour = $shiftStart->diffInHours(($shiftEnd)) ?? 0;
        $totalWorkingHour = $workingHour * $totalWorkingDays ?? 0;
        $hourlyRate = $totalWorkingHour > 0 ? $baseSalary / $totalWorkingHour : 0;

        $totalReimbursement = $user->totalReimbursement->total ?? 0;
        $totalOvertime = $user->totalOvertime->total ?? 0;
        $overtimeBonus = $totalOvertime * $hourlyRate ?? 0;

        $absenceDeduction = $totalAbsence / $totalWorkingDays * $baseSalary;

        $earnings = $baseSalary + $totalReimbursement + $overtimeBonus;
        $deductions = $absenceDeduction;

        $takeHomePay = $earnings - $deductions;

        $user->payslips()->create([
            'payroll_id' => $payroll->id,
            'base_salary' => $baseSalary,
            'total_reimbursement' => $totalReimbursement,
            'total_overtime_hours' => $totalOvertime,
            'overtime_bonus' => $overtimeBonus,
            'absence_deduction' => $absenceDeduction,
            'total_absence' => $totalAbsence,
            'take_home_pay' => $takeHomePay,
        ]);

        return true;
    }
}
