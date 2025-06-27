<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\AttendanceResource;
use App\Services\AttendanceService;
use App\Traits\ApiResponse;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

#[Group('Attendance')]
class AttendanceController extends Controller
{
    /**
     * Submit check-in or check-out
     */
    public function submit(Request $request, AttendanceService $service)
    {
        [$att, $statusCode] = $service->checkInOrOut(Auth::user());

        return AttendanceResource::make($att)->additional([
            'status' => 'success',
            'message' => $statusCode === 201
                ? 'Successfully checked in for today.'
                : 'Successfully checked out for today.'
        ])->response()->setStatusCode($statusCode);
    }
}
