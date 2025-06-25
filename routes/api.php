<?php

use App\Http\Controllers\Api\V1\AttendancePeriodController;
use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.v1.')->group(function () {
    Route::post('/auth', [AuthController::class, 'authenticate']);

    Route::middleware('auth:sanctum')->group(function () {
        // Route::apiResource('attendance-periods', AttendancePeriodController::class);
    });
});
