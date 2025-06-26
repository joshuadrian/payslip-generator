<?php

use App\Models\AttendancePeriod;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('fail on accept json only middleware', function () {
    $user = User::factory()->create();
    $token = $user->createToken('api-token')->plainTextToken;
    $response = $this->withHeaders([
        'Authorization' => "Bearer $token"
    ])->post("/api/v1/attendances/submit");

    $response->assertStatus(406);
});

test('fail to authenticate because no authorization header', function () {
    $user = User::factory()->create();
    $token = $user->createToken('api-token')->plainTextToken;
    $response = $this->postJson("/api/v1/attendances/submit");

    $response->assertStatus(401);
});

test('fail on user not found', function () {
    $user = User::factory()->create();
    $token = $user->createToken('api-token')->plainTextToken;
    $response = $this->withHeaders([
        'Authorization' => "Bearer wrong"
    ])->postJson("/api/v1/attendances/wrong");

    $response->assertStatus(404);
});

test('fail on missing attendance period existence', function () {
    $user = User::factory()->create();
    $token = $user->createToken('api-token')->plainTextToken;
    $date = now()->format('Y-m-d');

    $response = $this->withHeaders([
        'Authorization' => "Bearer $token"
    ])->postJson("/api/v1/attendances/submit");

    $response->assertJson(['message' => "Attendance period doesn't exists for today"]);
    $response->assertStatus(422);
});

test('fail on checking in or out on weekends', function () {
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
