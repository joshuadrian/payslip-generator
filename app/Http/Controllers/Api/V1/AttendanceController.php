<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\AttendanceService;
use App\Traits\ApiResponse;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

#[Group('Attendance')]
class AttendanceController extends Controller
{
    use ApiResponse;
    /**
     * Submit attendance check-in or check-out of the specified user
     */
    public function submit(Request $request, AttendanceService $service)
    {
        [$att, $statusCode] = $service->checkInOrOut(Auth::user());

        /**
         * Success
         *
         * @body array{
         *      status:'success',
         *      message: 'Successfully checked in for today.',
         *      data: array<App\Models\Attendance>
         *  }
         */
        return $this->success("Successfully checked in for today.", $att, $statusCode);
    }
}
