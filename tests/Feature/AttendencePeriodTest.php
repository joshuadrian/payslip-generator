<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('fail on accept json only middleware', function () {
    $response = $this->post('/api/v1/attendance-periods');

    $response->assertStatus(406);
});

test('fail to authenticate because no authorization header', function () {
    $response = $this->postJson('/api/v1/attendance-periods');

    $response->assertStatus(401);
});

test('fail on validation checks', function () {
    $user = User::factory()->create();
    $token = $user->createToken('api-token')->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => "Bearer $token"
    ])->postJson('/api/v1/attendance-periods', [
        'wrong' => 'wrong',
        'wrong' => 'wrong'
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
