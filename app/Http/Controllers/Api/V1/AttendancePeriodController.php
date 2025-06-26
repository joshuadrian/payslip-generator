<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\UniqueAttendancePeriodException;
use App\Http\Controllers\Controller;
use App\Models\AttendancePeriod;
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
    public function store(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d',
        ]);

        $prevAp = AttendancePeriod::where([
            ['start_date', Carbon::parse($request->start_date)->startOfDay()],
            ['end_date', Carbon::parse($request->end_date)->startOfDay()]
        ])->count();

        if ($prevAp > 0) {
            throw new UniqueAttendancePeriodException;
        }

        $ap = AttendancePeriod::create([
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ])->refresh();

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
