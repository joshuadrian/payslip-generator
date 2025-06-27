<?php

namespace App\Services;

use App\Exceptions\AttendancePeriodLockedException;
use App\Jobs\ProcessPayrollChunk;
use App\Models\AttendancePeriod;
use App\Models\Payroll;
use App\Models\User;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;

class PayrollService
{
    public function run(AttendancePeriod $period)
    {
        if ($period->is_locked) {
            throw new AttendancePeriodLockedException;
        }

        return DB::transaction(function () use ($period) {
            $payroll = Payroll::create([
                'attendance_period_id' => $period->id
            ])->refresh();

            $period->update(['is_locked' => true]);

            $employeesChunk = User::employee()->with([
                'latestSalary',
                'totalReimbursement',
                'totalOvertime',
                'attendances' => fn($q) => $q->whereBetween('date', [
                    $period->start_date,
                    $period->end_date
                ])
            ])->get()->chunk(50);

            $jobs = [];

            foreach ($employeesChunk as $employees) {
                $jobs[] = new ProcessPayrollChunk($payroll, $employees, $period);
            }

            Bus::batch($jobs)

                ->before(function (Batch $batch) use ($payroll) {
                    logger('Processing payroll. Batch ID: ' . $batch->id);
                    $payroll->update(['message' => 'Processing payroll.']);
                })

                ->then(function (Batch $batch) use ($payroll) {
                    logger('All chunks processed');
                    $payroll->update(['is_ready' => true, 'message' => 'Payroll is ready.']);
                })

                ->catch(function (Batch $batch, \Throwable $e) use ($period, $payroll) {
                    logger()->error('Chunk processing failed.');
                    $period->update(['is_locked' => false]);
                    $payroll->update(['message' => 'Failed to process payroll. Batch ID: ' . $batch->id]);
                })

                ->dispatch();

            return $payroll;
        });
    }
}
