<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('fail to authenticate because no authorization header', function () {
    $response = $this->getJson('/api/v1/attendance-periods');

    $response->assertStatus(401);
});

test('fail to authenticate because of wrong authorization token with no user at all', function () {
    $response = $this->withHeaders([
        'Authorization' => "Bearer wrong"
    ])->getJson('/api/v1/attendance-periods');

    $response->assertStatus(401);
});

test('fail to authenticate because of wrong authorization token with at least 1 user', function () {
    User::factory()->create();
    $response = $this->withHeaders([
        'Authorization' => "Bearer wrong"
    ])->getJson('/api/v1/attendance-periods');

    $response->assertStatus(401);
});

test('successfully get all attendance period', function () {
    $user = User::factory()->create();
    $token = $user->createToken('api-token')->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => "Bearer $token"
    ])->getJson('/api/v1/attendance-periods');

    $response->assertStatus(200);
});
