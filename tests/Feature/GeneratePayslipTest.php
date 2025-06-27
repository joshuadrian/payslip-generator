<?php

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
    $user = User::factory()->employee()->create();
    $this->actingAs($user, 'sanctum');
});

test('successfully generated payslip', function () {
    $response = $this->getJson("/api/v1/payslips/generate");
    $response->assertStatus(200);
});
