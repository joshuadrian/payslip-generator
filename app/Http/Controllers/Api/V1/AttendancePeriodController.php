<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\UniqueAttendancePeriodException;
use App\Http\Controllers\Controller;
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request, AttendancePeriodService $service)
    {
        $request->validate([
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d',
        ]);

        $ap = $service->store($request);

        /**
         * Success
         *
         * @body array{
         *      status:'success',
         *      message: 'Successfully created new attendance period.',
         *      data: AttendancePeriod
         *  }
         */
        return $this->success('Successfully created new attendance period.', $ap, 201);
    }
}
