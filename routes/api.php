<?php

use App\Http\Controllers\Api\V1\AttendancePeriodController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\AttendanceController;
use App\Http\Controllers\Api\V1\OvertimeController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\ReimbursementController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.v1.')->group(function () {
    Route::get('/users', [UserController::class, 'index']);

    Route::post('/auth', [AuthController::class, 'authenticate']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::middleware('can:create attendance period')
            ->post('attendance-periods', [AttendancePeriodController::class, 'store']);

        Route::middleware('can:create attendance')
            ->post('attendances/submit', [AttendanceController::class, 'submit']);

        Route::middleware('can:create overtime')
            ->post('overtimes', [OvertimeController::class, 'store']);

        Route::middleware('can:create reimbursement')
        ->post('reimbursements', [ReimbursementController::class, 'store']);
    });
});
