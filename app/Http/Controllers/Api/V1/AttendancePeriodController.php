<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\AttendancePeriodResource;
use App\Models\AttendancePeriod;
use App\Traits\ApiResponse;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

#[Group('Attendance Period')]
class AttendancePeriodController extends Controller
{
    use ApiResponse;
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'start_date' => 'required|date_format:Y-m-d',
                'end_date' => 'required|date_format:Y-m-d',
            ]);

            $ap = AttendancePeriod::create([
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ])->refresh();

            /**
             * Success
             *
             * @body array{
             *      status:'success',
             *      message: 'Successfully created new attendance period',
             *      data: AttendancePeriod
             *  }
             */
            return $this->success(
                'Successfully created new attendance period',
                $ap->toResource(AttendancePeriodResource::class),
                200
            );
        } catch (UniqueConstraintViolationException $e) {
            return $this->error('Attendance period already exist', [], 409);
        } catch (ValidationException $e) {
            return $this->error('Unprocessable Entity', $e->errors(), 422);
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->error('Internal server error', [], 500);
        }
    }
}
