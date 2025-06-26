<?php

namespace App\Services;

use App\Exceptions\OvertimeSubmissionOnWorkingHoursException;
use App\Exceptions\OvertimeSubmittedException;
use App\Models\Overtime;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;

class OvertimeService
{
    public function store(User $user, Request $request)
    {
        $date = now()->startOfDay();
        $shiftEnd = Setting::where('name', 'shift_end')->first()->value ?? '17:00';
        $shiftEnd = now()->setTimeFromTimeString($shiftEnd);

        if (now() < $shiftEnd) {
            throw new OvertimeSubmissionOnWorkingHoursException;
        }

        $formattedDate = $date->format('Y-m-d');
        $prevOvt = Overtime::where([['user_id', $user->id], ['date', $date]])->first();

        if (!empty($prevOvt)) {
            throw new OvertimeSubmittedException;
        }

        $ovt = Overtime::create([
            'user_id' => $user->id,
            'date' => $formattedDate,
            'duration_hours' => $request->duration_hours
        ])->refresh();

        return $ovt;
    }
}
