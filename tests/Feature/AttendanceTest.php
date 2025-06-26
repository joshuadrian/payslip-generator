<?php

use App\Models\AttendancePeriod;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('fail on missing attendance period existence', function () {
    $user = User::factory()->create();
    $token = $user->createToken('api-token')->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => "Bearer $token"
    ])->postJson("/api/v1/attendances/submit");

    $response->assertJson(['message' => "Attendance period doesn't exists for today"]);
    $response->assertStatus(422);
});

test('fail on checking in on weekends', function () {
    Carbon::setTestNow(now()->endOfWeek());

    $user = User::factory()->create();
    $token = $user->createToken('api-token')->plainTextToken;

    AttendancePeriod::create([
        'start_date' => now()->startOfMonth(),
        'end_date' => now()->endOfMonth()
    ]);

    $response = $this->withHeaders([
        'Authorization' => "Bearer $token"
    ])->postJson("/api/v1/attendances/submit");

    $response->assertJson(['message' => 'Check in or check out cannot be done in weekends']);
    $response->assertStatus(422);
});

test('successfully checked in', function () {
    Carbon::setTestNow(now()->startOfWeek()->startOfDay());

    $user = User::factory()->create();
    $token = $user->createToken('api-token')->plainTextToken;

    AttendancePeriod::create([
        'start_date' => now()->startOfMonth()->format('Y-m-d'),
        'end_date' => now()->endOfMonth()->format('Y-m-d')
    ]);

    $response = $this->withHeaders([
        'Authorization' => "Bearer $token"
    ])->postJson("/api/v1/attendances/submit");

    $response->assertStatus(200);
});

test('successfully checked out', function () {
    Carbon::setTestNow(now()->startOfWeek()->startOfDay());

    $user = User::factory()->create();
    $token = $user->createToken('api-token')->plainTextToken;

    AttendancePeriod::create([
        'start_date' => now()->startOfMonth()->format('Y-m-d'),
        'end_date' => now()->endOfMonth()->format('Y-m-d')
    ]);

    $response = $this->withHeaders([
        'Authorization' => "Bearer $token"
    ])->postJson("/api/v1/attendances/submit");

    $response->assertStatus(200);

    Carbon::setTestNow(now()->startOfWeek()->startOfDay()->addHour(7));

    $response = $this->withHeaders([
        'Authorization' => "Bearer $token"
    ])->postJson("/api/v1/attendances/submit");

    $response->assertStatus(200);
});
