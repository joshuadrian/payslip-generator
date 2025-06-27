<?php

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\SettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
    $this->seed(SettingSeeder::class);

    $user = User::factory()->employee()->create();
    $this->actingAs($user, 'sanctum');
});

test('fail on validation when amount is missing', function () {
    $response = $this->postJson('/api/v1/reimbursements', [
        'description' => 'Lunch with client'
    ]);

    $response->assertStatus(422)
        ->assertJson(['errors' => ['amount' => ['The amount field is required.']]]);
});

test('fail on validation when amount is not decimal', function () {
    $response = $this->postJson('/api/v1/reimbursements', [
        'amount' => 'invalid_amount',
        'description' => 'Taxi to office'
    ]);

    $response->assertStatus(422)
        ->assertJson(['errors' => ['amount' => ['The amount field must have 0-2 decimal places.']]]);
});

test('fail on validation when description is not a string', function () {
    $response = $this->postJson('/api/v1/reimbursements', [
        'amount' => 50.25,
        'description' => ['unexpected', 'array']
    ]);

    $response->assertStatus(422)
        ->assertJson(['errors' => ['description' => ['The description field must be a string.']]]);
});

test('successfully submits reimbursement', function () {
    $response = $this->postJson('/api/v1/reimbursements', [
        'amount' => 125.50,
        'description' => 'Hotel for business trip'
    ]);

    $response->assertStatus(201);
});
