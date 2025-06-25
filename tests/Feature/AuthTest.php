<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('failed validation checks', function () {
    $response = $this->post('/api/v1/auth', [
        'usernames' => 'test',
        'passwords' => 'password'
    ]);

    $response->assertStatus(422);
});

test('user fail to authenticate with wrong username', function () {
    $user = User::factory()->create();
    $response = $this->post('/api/v1/auth', [
        'username' => 'test',
        'password' => $user->password
    ]);

    $response->assertStatus(401);
});

test('user fail to authenticate with wrong password', function () {
    $user = User::factory()->create();
    $response = $this->post('/api/v1/auth', [
        'username' => $user->username,
        'password' => 'test'
    ]);

    $response->assertStatus(401);
});

test('user fail to authenticate with wrong username and password', function () {
    User::factory()->create();
    $response = $this->post('/api/v1/auth', [
        'username' => 'test',
        'password' => 'test'
    ]);

    $response->assertStatus(401);
});

test('user can authenticate', function () {
    $user = User::factory()->create();
    $response = $this->post('/api/v1/auth', [
        'username' => $user->username,
        'password' => 'password'
    ]);

    $response->assertStatus(200);
});
