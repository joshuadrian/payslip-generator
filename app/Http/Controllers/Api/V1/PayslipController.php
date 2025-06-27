<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\GeneratedPayslipResource;
use App\Models\Payslip;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Support\Facades\Auth;

#[Group('Payslips')]
class PayslipController extends Controller
{
    /**
     * Generate payslip
     */
    public function generate(Request $request)
    {
        $ps = Payslip::where('user_id', Auth::id())->first();
        return GeneratedPayslipResource::make($ps)->additional([
            'status' => 'success',
            'message' => 'Generated payslip.'
        ])->response()->setStatusCode(200);
    }
}
