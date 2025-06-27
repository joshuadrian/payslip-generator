<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\AttendancePeriod;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\PayrollResource;
use App\Models\Payroll;
use App\Services\PayrollService;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    /**
     * List all payrolls
     */
    public function index()
    {
        return PayrollResource::collection(Payroll::all())->additional([
            'status' => 'success',
            'message' => 'Fetched all payrolls'
        ])->response()->setStatusCode(200);
    }

    /**
     * Create payroll
     */
    public function run(AttendancePeriod $period, PayrollService $service)
    {
        $pr = $service->run($period);

        return PayrollResource::make($pr)->additional([
            'status' => 'success',
            'message' => 'Payroll is being processed.'
        ])->response()->setStatusCode(201);
    }

    /**
     * Show payroll
     */
    public function show(Request $request, Payroll $payroll)
    {
        $pr = $payroll->load(['payslips', 'attendancePeriod']);
        $startDate = $payroll->attendancePeriod->start_date->format('Y-m-d');
        $endDate = $payroll->attendancePeriod->end_date->format('Y-m-d');

        return PayrollResource::make($pr)->additional([
            'status' => 'success',
            'message' => "Fetched payroll for ($startDate - $endDate)"
        ])->response()->setStatusCode(200);
    }
}
