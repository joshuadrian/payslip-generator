<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\OvertimeSubmissionOnWorkingHoursException;
use App\Exceptions\OvertimeSubmittedException;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\OvertimeService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class OvertimeController extends Controller
{
    use ApiResponse;

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, OvertimeService $service)
    {
        try {
            $maxDuration = (float) Setting::where('name', 'max_overtime_duration')->first()->value ?? 3;
            $request->validate([
                // @var int
                'duration_hours' => "required|numeric|max:$maxDuration|min:0.01"
            ]);
            $ovt = $service->submitOvertime(Auth::user(), $request->duration_hours);

            /**
             * Success
             *
             * @body array{
             *      status:'success',
             *      message: 'Successfully submitted overtime for today',
             *      data: array<App\Models\Overtime>
             *  }
             */
            return $this->success("Successfully submitted overtime for today", $ovt, 200);
        } catch (ValidationException $e) {
            return $this->error('Unprocessable Entity', $e->errors(), 422);
        } catch (OvertimeSubmittedException $e) {
            return $this->error($e->getMessage(), [], 409);
        } catch (OvertimeSubmissionOnWorkingHoursException $e) {
            return $this->error($e->getMessage(), [], 409);
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->error('Internal server error', [], 500);
        }
    }
}
