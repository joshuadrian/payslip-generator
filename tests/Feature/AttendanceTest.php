<?php

use App\Models\AttendancePeriod;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
});

test('fail on missing attendance period existence', function () {
    $user = User::factory()->employee()->create();
    $token = $user->createToken('api-token')->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => "Bearer $token"
    ])->postJson("/api/v1/attendances/submit");

    $response->assertStatus(409)
        ->assertJson(['message' => "Attendance period doesn't exists for today."]);
});

test('fail on checking in on weekends', function () {
    Carbon::setTestNow(now()->endOfWeek());

    $user = User::factory()->employee()->create();
    $token = $user->createToken('api-token')->plainTextToken;

    AttendancePeriod::create([
        'start_date' => now()->startOfMonth(),
        'end_date' => now()->endOfMonth()
    ]);

    $response = $this->withHeaders([
        'Authorization' => "Bearer $token"
    ])->postJson("/api/v1/attendances/submit");

    $response->assertStatus(422)
        ->assertJson(['message' => 'Check in or check out cannot be done in weekends.']);
});

test('successfully checked in', function () {
    Carbon::setTestNow(now()->startOfWeek()->startOfDay());

    $user = User::factory()->employee()->create();
    $token = $user->createToken('api-token')->plainTextToken;

    AttendancePeriod::create([
        'start_date' => now()->startOfMonth()->format('Y-m-d'),
        'end_date' => now()->endOfMonth()->format('Y-m-d')
    ]);

    $response = $this->withHeaders([
        'Authorization' => "Bearer $token"
    ])->postJson("/api/v1/attendances/submit");

    $response->assertStatus(201);
});

test('successfully checked out', function () {
    Carbon::setTestNow(now()->startOfWeek()->startOfDay());

    $user = User::factory()->employee()->create();
    $token = $user->createToken('api-token')->plainTextToken;

    AttendancePeriod::create([
        'start_date' => now()->startOfMonth()->format('Y-m-d'),
        'end_date' => now()->endOfMonth()->format('Y-m-d')
    ]);

    $response = $this->withHeaders([
        'Authorization' => "Bearer $token"
    ])->postJson("/api/v1/attendances/submit");

    $response->assertStatus(201);

    Carbon::setTestNow(now()->startOfWeek()->startOfDay()->addHour(7));

    $response = $this->withHeaders([
        'Authorization' => "Bearer $token"
    ])->postJson("/api/v1/attendances/submit");

    $response->assertStatus(200);
});
