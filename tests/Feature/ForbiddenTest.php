<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Attendance periods
test('fail on attendance periods route', function () {
    $user = User::factory()->create();
    $token = $user->createToken('api-token')->plainTextToken;
    $response = $this->withHeaders([
        'Authorization' => "Bearer $token"
    ])->postJson('/api/v1/attendance-periods');
    $response->assertStatus(403);
});

// Attendances
test('fail on attendance route', function () {
    $user = User::factory()->create();
    $token = $user->createToken('api-token')->plainTextToken;
    $response = $this->withHeaders([
        'Authorization' => "Bearer $token"
    ])->postJson("/api/v1/attendances/submit");

    $response->assertStatus(403);
});

// Overtimes
test('fail on overtimes route', function () {
    $user = User::factory()->create();
    $token = $user->createToken('api-token')->plainTextToken;
    $response = $this->withHeaders([
        'Authorization' => "Bearer $token"
    ])->postJson("/api/v1/overtimes");

    $response->assertStatus(403);
});
