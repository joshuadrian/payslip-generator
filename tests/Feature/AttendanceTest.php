<?php

use App\Models\AttendancePeriod;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);

    $user = User::factory()->employee()->create();
    $this->actingAs($user, 'sanctum');
});

test('fail on missing attendance period existence', function () {
    $response = $this->postJson("/api/v1/attendances/submit");

    $response->assertStatus(409)
        ->assertJson(['message' => "Attendance period doesn't exists for today."]);
});

test('fail on checking in on weekends', function () {
    Carbon::setTestNow(now()->endOfWeek());

    AttendancePeriod::create([
        'start_date' => now()->startOfMonth(),
        'end_date' => now()->endOfMonth()
    ]);

    $response = $this->postJson("/api/v1/attendances/submit");

    $response->assertStatus(422)
        ->assertJson(['message' => 'Check in or check out cannot be done in weekends.']);
});

test('successfully checked in', function () {
    Carbon::setTestNow(now()->startOfWeek()->startOfDay());

    AttendancePeriod::create([
        'start_date' => now()->startOfMonth()->format('Y-m-d'),
        'end_date' => now()->endOfMonth()->format('Y-m-d')
    ]);

    $response = $this->postJson("/api/v1/attendances/submit");
    $response->assertStatus(201);
});

test('successfully checked out', function () {
    Carbon::setTestNow(now()->startOfWeek()->startOfDay());

    AttendancePeriod::create([
        'start_date' => now()->startOfMonth()->format('Y-m-d'),
        'end_date' => now()->endOfMonth()->format('Y-m-d')
    ]);

    $response = $this->postJson("/api/v1/attendances/submit");
    $response->assertStatus(201);

    Carbon::setTestNow(now()->startOfWeek()->startOfDay()->addHour(7));

    $response = $this->postJson("/api/v1/attendances/submit");
    $response->assertStatus(200);
});
