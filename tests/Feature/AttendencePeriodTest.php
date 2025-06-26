<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('fail on validation when required fields are missing', function () {
    $user = User::factory()->create();
    $token = $user->createToken('api-token')->plainTextToken;
    $response = $this->withHeaders([
        'Authorization' => "Bearer $token"
    ])->postJson('/api/v1/attendance-periods', [
        'wrong' => '2025-01-01',
        'wrong' => '2025-01-31'
    ]);

    $response->assertJson([
        'errors' => [
            'start_date' => ['The start date field is required.'],
            'end_date' => ['The end date field is required.']
        ]
    ]);
    $response->assertStatus(422);
});

test('fail to create because same data already exists', function () {
    $user = User::factory()->create();
    $token = $user->createToken('api-token')->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => "Bearer $token"
    ])->postJson('/api/v1/attendance-periods', [
        'start_date' => '2025-01-01',
        'end_date' => '2025-01-31'
    ]);

    $response = $this->withHeaders([
        'Authorization' => "Bearer $token"
    ])->postJson('/api/v1/attendance-periods', [
        'start_date' => '2025-01-01',
        'end_date' => '2025-01-31'
    ]);

    $response->assertStatus(409);
});

test('successfully created attendance period', function () {
    $user = User::factory()->create();
    $token = $user->createToken('api-token')->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => "Bearer $token"
    ])->postJson('/api/v1/attendance-periods', [
        'start_date' => '2025-01-01',
        'end_date' => '2025-01-31'
    ]);

    $response->assertStatus(200);
});
