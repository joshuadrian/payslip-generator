<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\AttendanceCheckedOutException;
use App\Exceptions\AttendancePeriodNotFoundException;
use App\Exceptions\CheckInOrOutOnWeekendsException;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AttendanceService;
// use App\Services\AttendanceService;
use App\Traits\ApiResponse;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

#[Group('Attendance')]
class AttendanceController extends Controller
{
    use ApiResponse;
    /**
     * Store check-in or check-out attendance of the specified user
     */
    public function check(Request $request, User $user, AttendanceService $service)
    {
        try {
            $date = now()->startOfDay();
            $att = $service->checkInOrOut($user, $date);
            /**
             * Success
             *
             * @body array{
             *      status:'success',
             *      message: 'Successfully checked in for <date>',
             *      data: array<Attendance>
             *  }
             */
            return $this->success("Successfully checked in for today", $att, 200);
        } catch (AttendanceCheckedOutException $e) {
            return $this->error($e->getMessage(), [], 409);
        } catch (ValidationException $e) {
            return $this->error('Unprocessable Entity', $e->errors(), 422);
        } catch (AttendancePeriodNotFoundException $e) {
            return $this->error($e->getMessage(), [], 422);
        } catch (CheckInOrOutOnWeekendsException $e) {
            return $this->error($e->getMessage(), [], 422);
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->error('Internal server error', [], 500);
        }
    }
}
