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
});

test('fail on validation when required fields are missing', function () {
    $user = User::factory()->employee()->create();
    $token = $user->createToken('api-token')->plainTextToken;
    $response = $this->withHeaders([
        'Authorization' => "Bearer $token"
    ])->postJson("/api/v1/overtimes", [
        'wrong' => 3
    ]);

    $response->assertJson(['errors' => ['duration_hours' => ['The duration hours field is required.']]]);
    $response->assertStatus(422);
});

test('fail on validation when duration is under the requirements', function () {
    Carbon::setTestNow(now()->startOfDay()->setHour(17));
    $user = User::factory()->employee()->create();
    $token = $user->createToken('api-token')->plainTextToken;
    $response = $this->withHeaders([
        'Authorization' => "Bearer $token"
    ])->postJson("/api/v1/overtimes", [
        'duration_hours' => 0
    ]);

    $response->assertJson(['errors' => ['duration_hours' => ['The duration hours field must be at least 0.01.']]]);
    $response->assertStatus(422);
});

test('fail on validation when duration is above the requirements', function () {
    Carbon::setTestNow(now()->startOfDay()->setHour(17));
    $user = User::factory()->employee()->create();
    $token = $user->createToken('api-token')->plainTextToken;
    $response = $this->withHeaders([
        'Authorization' => "Bearer $token"
    ])->postJson("/api/v1/overtimes", [
        'duration_hours' => 3.1
    ]);

    $response->assertJson(['errors' => ['duration_hours' => ['The duration hours field must not be greater than 3.']]]);
    $response->assertStatus(422);
});

test('successfully submitted overtime', function () {
    Carbon::setTestNow(now()->startOfDay()->setHour(17));
    $user = User::factory()->employee()->create();
    $token = $user->createToken('api-token')->plainTextToken;
    $response = $this->withHeaders([
        'Authorization' => "Bearer $token"
    ])->postJson("/api/v1/overtimes", [
        'duration_hours' => 2
    ]);

    $response->assertStatus(201);
});
