<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\OvertimeResource;
use App\Models\Setting;
use App\Services\OvertimeService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OvertimeController extends Controller
{
    use ApiResponse;

    /**
     * Create overtime
     */
    public function store(Request $request, OvertimeService $service)
    {
        $maxDuration = (float) Setting::where('name', 'max_overtime_duration')->first()->value ?? 3;
        $request->validate([
            // @var int
            'duration_hours' => "required|numeric|max:$maxDuration|min:0.01"
        ]);
        $ovt = $service->store(Auth::user(), $request);

        return OvertimeResource::make($ovt)->additional([
            'status' => 'success',
            'message' => 'Successfully submitted overtime for today.'
        ])->response()->setStatusCode(201);
    }
}
