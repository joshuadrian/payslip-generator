<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\AttendancePeriodResource;
use App\Models\AttendancePeriod;
use App\Traits\ApiResponse;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

#[Group('Attendance Period')]

class AttendancePeriodController extends Controller
{
    use ApiResponse;
    /**
     *
     * Display a listing of the resource.
     */
    public function index(Request $reqest)
    {
        $ap = AttendancePeriod::all();

        /**
         * Success
         *
         * @body array{
         *      status:'success',
         *      message: 'Successfully fetched attendance period data',
         *      data: array<AttendancePeriod>
         *  }
         */
        return $this->success('Successfully fetched attendance period data', $ap, 200);
    }

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
        } catch (ValidationException $e) {
            return $this->error('Unprocessable Entity', $e->errors(), 422);
        } catch (\Throwable $th) {
            Log::error($th);

            /**
             * Internal server error
             */
            return $this->error('Internal server error', [], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    // public function show(AttendancePeriod $attendancePeriod)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, AttendancePeriod $attendancePeriod)
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(AttendancePeriod $attendancePeriod)
    // {
    //     //
    // }
}
