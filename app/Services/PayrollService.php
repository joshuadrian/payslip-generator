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
                'attendances' => fn($q) => $q->whereBetween('date', [
                    $period->start_date,
                    $period->end_date
                ])
            ])->get()->chunk(50);

            $jobs = [];

            foreach ($employeesChunk as $employees) {
                $jobs[] = new ProcessPayrollChunk($employees);
            }

            Bus::batch($jobs)
                ->before(function (Batch $batch) {
                    logger('Batch ID: ' . $batch->id);
                })
                ->then(function (Batch $batch) {
                    logger('All chunks processed');
                })
                ->catch(function (Batch $batch, \Throwable $e) {
                    logger('Chunk processing failed: ' . $e->getMessage());
                })
                ->dispatch();

            return $payroll;
        });
    }
}
