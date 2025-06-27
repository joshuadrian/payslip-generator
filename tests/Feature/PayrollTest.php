<?php

use App\Models\User;
use App\Models\AttendancePeriod;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\SettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
    $this->seed(SettingSeeder::class);

    $user = User::factory()->admin()->create();
    $this->actingAs($user, 'sanctum');
});

test('fail on locked period', function () {
    $period = AttendancePeriod::create([
        'start_date' => '2025-01-01',
        'end_date' => '2025-01-31',
        'is_locked' => true,
    ]);
    $response = $this->postJson("/api/v1/payrolls/run/{$period->uid}");

    $response->assertStatus(422)
        ->assertJson(['message' => 'Payroll already processed.']);
});

test('successfully ran payroll', function () {
    $period = AttendancePeriod::create([
        'start_date' => '2025-01-01',
        'end_date' => '2025-01-31',
    ]);
    $response = $this->postJson("/api/v1/payrolls/run/{$period->uid}");

    $response->assertStatus(201);
});
