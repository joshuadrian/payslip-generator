<?php

namespace App\Http\Controllers\Api\V1;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Dedoc\Scramble\Attributes\Group;
use App\Services\ReimbursementService;
use App\Http\Resources\Api\V1\ReimbursementResource;

#[Group('Reimbursements')]
class ReimbursementController extends Controller
{
    /**
     * Create a new reimbursement submission
     */
    public function store(Request $request, ReimbursementService $service)
    {
        $request->validate([
            'amount' => 'required|decimal:0,2',
            'description' => 'string',
        ]);

        $reim = $service->store($request);

        return ReimbursementResource::make($reim)->additional([
            'status' => 'success',
            'message' => 'Successfully submitted reimbursement request.'
        ])->response()->setStatusCode(201);
    }
}
