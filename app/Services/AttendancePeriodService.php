<?php

namespace App\Services;

use App\Exceptions\UniqueAttendancePeriodException;
use App\Models\AttendancePeriod;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendancePeriodService
{
    public function store(Request $request)
    {
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

        return $ap;
    }
}
