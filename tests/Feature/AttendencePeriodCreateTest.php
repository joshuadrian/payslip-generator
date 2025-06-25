<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('fail to authenticate because no authorization header', function () {
    $response = $this->postJson('/api/v1/attendance-periods');

    $response->assertStatus(401);
});

test('fail to authenticate because of wrong authorization token with no user at all', function () {
    $response = $this->withHeaders([
        'Authorization' => "Bearer wrong"
    ])->postJson('/api/v1/attendance-periods');

    $response->assertStatus(401);
});

test('fail to authenticate because of wrong authorization token with at least 1 user', function () {
    User::factory()->create();
    $response = $this->withHeaders([
        'Authorization' => "Bearer wrong"
    ])->postJson('/api/v1/attendance-periods');

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

    $response->assertStatus(422);
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
