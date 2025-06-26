<?php

namespace App\Services;

use App\Exceptions\AttendanceCheckedOutException;
use App\Exceptions\AttendancePeriodNotFoundException;
use App\Exceptions\CheckInOrOutOnWeekendsException;
use App\Models\Attendance;
use App\Models\AttendancePeriod;
use App\Models\User;

class AttendanceService
{
    public function checkInOrOut(User $user)
    {
        $date = now()->startOfDay();
        $formattedDate = $date->format('Y-m-d');

        if ($date->isWeekend()) {
            throw new CheckInOrOutOnWeekendsException;
        }

        $prevAtt = Attendance::where([['user_id', $user->id], ['date', $date]])->first();

        if (empty($prevAtt)) {
            $period = AttendancePeriod::where([['start_date', "<=", $date], ['end_date', '>=', $date]])->first();

            if (empty($period)) {
                throw new AttendancePeriodNotFoundException;
            }

            $att = Attendance::create([
                'user_id' => $user->id,
                'date' => $formattedDate,
                'attendance_period_id' => $period->id
            ])->refresh();
        } else if (empty($prevAtt->checked_out_at)) {
            $prevAtt->update(['checked_out_at' => now()]);
            $att = $prevAtt->refresh();
        } else {
            throw new AttendanceCheckedOutException;
        }

        return $att;
    }
}
