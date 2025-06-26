<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\OvertimeService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OvertimeController extends Controller
{
    use ApiResponse;

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, OvertimeService $service)
    {
        $maxDuration = (float) Setting::where('name', 'max_overtime_duration')->first()->value ?? 3;
        $request->validate([
            // @var int
            'duration_hours' => "required|numeric|max:$maxDuration|min:0.01"
        ]);
        $ovt = $service->store(Auth::user(), $request);

        /**
         * Success
         *
         * @body array{
         *      status:'success',
         *      message: 'Successfully submitted overtime for today.',
         *      data: array<App\Models\Overtime>
         *  }
         */
        return $this->success("Successfully submitted overtime for today.", $ovt, 201);
    }
}
