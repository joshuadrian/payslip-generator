<?php

namespace App\Jobs;

use App\Models\AttendancePeriod;
use App\Models\Payroll;
use App\Services\PayslipService;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

class ProcessPayrollChunk implements ShouldQueue
{
    use Queueable, Batchable;

    protected Collection $chunk;
    protected AttendancePeriod $period;
    protected Payroll $payroll;

    /**
     * Create a new job instance.
     */
    public function __construct(Payroll $payroll, Collection $chunk, AttendancePeriod $period)
    {
        $this->chunk = $chunk;
        $this->period = $period;
        $this->payroll = $payroll;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $currentUser = null;
        try {
            DB::transaction(function () use (&$currentUser) {
                foreach ($this->chunk as $user) {
                    $currentUser = $user;
                    $service = new PayslipService;
                    $service->run($this->payroll, $user, $this->period);
                }
            });
        } catch (\Throwable $th) {
            logger()->error("Failed to process payslip for user ID $currentUser->id: ");
            throw $th;
        }
    }
}
