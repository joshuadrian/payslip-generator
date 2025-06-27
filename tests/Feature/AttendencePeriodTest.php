<?php

use App\Models\AttendancePeriod;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);

    $user = User::factory()->admin()->create();
    $this->actingAs($user, 'sanctum');
});

test('fail on validation when required fields are missing', function () {
    $response = $this->postJson('/api/v1/attendance-periods', [
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
    AttendancePeriod::create([
        'start_date' => '2025-01-01',
        'end_date' => '2025-01-31'
    ]);

    $response = $this->postJson('/api/v1/attendance-periods', [
        'start_date' => '2025-01-01',
        'end_date' => '2025-01-31'
    ]);

    $response->assertStatus(409);
});

test('successfully created attendance period', function () {
    $response = $this->postJson('/api/v1/attendance-periods', [
        'start_date' => '2025-01-01',
        'end_date' => '2025-01-31'
    ]);

    $response->assertStatus(201);
});
