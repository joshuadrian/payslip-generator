<?php

use App\Models\User;
use Carbon\Carbon;
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

test('fail on validation when required fields are missing', function () {
    $response = $this->postJson("/api/v1/overtimes", [
        'wrong' => 3
    ]);

    $response->assertStatus(422)
        ->assertJson(['errors' => ['duration_hours' => ['The duration hours field is required.']]]);
});

test('fail on validation when duration is under the requirements', function () {
    Carbon::setTestNow(now()->startOfDay()->setHour(17));
    $response = $this->postJson("/api/v1/overtimes", [
        'duration_hours' => 0
    ]);

    $response->assertStatus(422)
        ->assertJson(['errors' => ['duration_hours' => ['The duration hours field must be at least 0.01.']]]);
});

test('fail on validation when duration is above the requirements', function () {
    Carbon::setTestNow(now()->startOfDay()->setHour(17));
    $response = $this->postJson("/api/v1/overtimes", [
        'duration_hours' => 3.1
    ]);

    $response->assertStatus(422)
        ->assertJson(['errors' => ['duration_hours' => ['The duration hours field must not be greater than 3.']]]);
});

test('successfully submitted overtime', function () {
    Carbon::setTestNow(now()->startOfDay()->setHour(17));
    $response = $this->postJson("/api/v1/overtimes", [
        'duration_hours' => 2
    ]);

    $response->assertStatus(201);
});
