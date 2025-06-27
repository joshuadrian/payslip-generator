<?php

use App\Models\AttendancePeriod;
use App\Models\Payroll;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Attendance periods
test('fail on attendance periods route', function () {
    $response = $this->postJson('/api/v1/attendance-periods');
    $response->assertStatus(401);

    $response = $this->withHeaders([
        'Authorization' => "Bearer wrong"
    ])->postJson('/api/v1/attendance-periods');
    $response->assertStatus(401);
});




// Attendances
test('fail on attendance route', function () {
    $response = $this->postJson("/api/v1/attendances/submit");
    $response->assertStatus(401);

    $response = $this->withHeaders([
        'Authorization' => "Bearer wrong"
    ])->postJson("/api/v1/attendances/submit");

    $response->assertStatus(401);
});




// Overtimes
test('fail on overtimes route', function () {
    $response = $this->postJson("/api/v1/overtimes");
    $response->assertStatus(401);

    $response = $this->withHeaders([
        'Authorization' => "Bearer wrong"
    ])->postJson("/api/v1/overtimes");

    $response->assertStatus(401);
});




// Reimbursements
test('fail on reimbursements route', function () {
    $response = $this->postJson("/api/v1/reimbursements");
    $response->assertStatus(401);

    $response = $this->withHeaders([
        'Authorization' => "Bearer wrong"
    ])->postJson("/api/v1/reimbursements");

    $response->assertStatus(401);
});




// Payrolls
test('fail on payrolls index route', function () {
    $response = $this->getJson("/api/v1/payrolls");
    $response->assertStatus(401);

    $response = $this->withHeaders([
        'Authorization' => "Bearer wrong"
    ])->getJson("/api/v1/payrolls");

    $response->assertStatus(401);
});

test('fail on payrolls show route', function () {
    $response = $this->getJson("/api/v1/payrolls/wrong");
    $response->assertStatus(401);

    $response = $this->withHeaders([
        'Authorization' => "Bearer wrong"
    ])->getJson("/api/v1/payrolls");

    $response->assertStatus(401);
});

test('fail on payrolls store route', function () {
    $response = $this->postJson("/api/v1/payrolls/run/wrong");
    $response->assertStatus(401);

    $response = $this->withHeaders([
        'Authorization' => "Bearer wrong"
    ])->postJson("/api/v1/payrolls/run/wrong");

    $response->assertStatus(401);
});
