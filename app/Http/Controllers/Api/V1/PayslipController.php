<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\GeneratedPayslipResource;
use App\Http\Resources\Api\V1\GeneratedPayslipSummaryResource;
use App\Models\Payroll;
use App\Models\Payslip;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Support\Facades\Auth;

#[Group('Payslips')]
class PayslipController extends Controller
{
    /**
     * Generate a payslip
     */
    public function generate(Request $request)
    {
        $ps = Payslip::where('user_id', Auth::id())->first();
        return GeneratedPayslipResource::make($ps)->additional([
            'status' => 'success',
            'message' => 'Generated payslip.'
        ])->response()->setStatusCode(200);
    }

    /**
     * Generate a payslip summary
     */
    public function generateSummary(Request $request, Payroll $payroll)
    {
        $startDate = $payroll->attendancePeriod->start_date->format('Y-m-d');
        $endDate = $payroll->attendancePeriod->end_date->format('Y-m-d');

        $userGroupedPs = Payslip::selectRaw('users.name, user_id, sum(take_home_pay) as total')
            ->join('users', 'users.id', '=', 'user_id')
            ->where('payroll_id', $payroll->id)
            ->groupBy('user_id', 'users.name')
            ->orderBy('total', 'desc')
            ->get();

        $totalPs = Payslip::selectRaw('sum(take_home_pay) as total')
            ->where('payroll_id', $payroll->id)
            ->first();

        return GeneratedPayslipSummaryResource::make([
            'total' => $totalPs,
            'details' => $userGroupedPs
        ])->additional([
            'status' => 'success',
            'message' => "Generated payslip summary for ($startDate - $endDate) payroll"
        ])->response()->setStatusCode(200);
    }
}
