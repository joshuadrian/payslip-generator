<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('fail on validation checks', function () {
    $response = $this->postJson('/api/v1/auth', [
        'wrong' => 'wrong',
        'wrong' => 'wrong'
    ]);

    $response->assertStatus(422);
});

test('fail to authenticate with wrong username', function () {
    $user = User::factory()->create();
    $response = $this->postJson('/api/v1/auth', [
        'username' => 'wrong',
        'password' => $user->password
    ]);

    $response->assertStatus(401);
});

test('fail to authenticate with wrong password', function () {
    $user = User::factory()->create();
    $response = $this->postJson('/api/v1/auth', [
        'username' => $user->username,
        'password' => 'wrong'
    ]);

    $response->assertStatus(401);
});

test('fail to authenticate with wrong username and password', function () {
    User::factory()->create();
    $response = $this->postJson('/api/v1/auth', [
        'username' => 'wrong',
        'password' => 'wrong'
    ]);

    $response->assertStatus(401);
});

test('successfully authenticated', function () {
    $user = User::factory()->create();
    $response = $this->postJson('/api/v1/auth', [
        'username' => $user->username,
        'password' => 'string'
    ]);

    $response->assertStatus(200);
});
