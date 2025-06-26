<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\UniqueAttendancePeriodException;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\AttendancePeriodResource;
use App\Models\AttendancePeriod;
use App\Services\AttendancePeriodService;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;

#[Group('Attendance Period')]
class AttendancePeriodController extends Controller
{
    use ApiResponse;
    /**
     * Create attendance period
     */
    public function store(Request $request, AttendancePeriodService $service)
    {
        $request->validate([
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d',
        ]);

        $ap = $service->store($request);

        return AttendancePeriodResource::make($ap)->additional([
            'status' => 'success',
            'message' => 'Successfully created new attendance period.'
        ])->response()->setStatusCode(201);
    }
}
