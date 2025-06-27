<?php

use App\Models\AttendancePeriod;
use App\Models\Payroll;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);
beforeEach(function () {
    $user = User::factory()->create();
    $this->actingAs($user, 'sanctum');
});

// Attendance periods
test('fail on attendance periods route', function () {
    $response = $this->postJson('/api/v1/attendance-periods');
    $response->assertStatus(403);
});




// Attendances
test('fail on attendance route', function () {
    $response = $this->postJson("/api/v1/attendances/submit");
    $response->assertStatus(403);
});




// Overtimes
test('fail on overtimes route', function () {
    $response = $this->postJson("/api/v1/overtimes");
    $response->assertStatus(403);
});




// Reimbursements
test('fail on reimbursements route', function () {
    $response = $this->postJson("/api/v1/reimbursements");
    $response->assertStatus(403);
});




// Payrolls
test('fail on payrolls index route', function () {
    $response = $this->getJson("/api/v1/payrolls");
    $response->assertStatus(403);
});

test('fail on payrolls show route', function () {
    $period = AttendancePeriod::create([
        'start_date' => '2025-01-01',
        'end_date' => '2025-01-31'
    ]);
    $pr = Payroll::create([
        'attendance_period_id' => $period->id
    ]);
    $response = $this->getJson("/api/v1/payrolls/$pr->uid");

    $response->assertStatus(403);
});

test('fail on payrolls store route', function () {
    $period = AttendancePeriod::create([
        'start_date' => '2025-01-01',
        'end_date' => '2025-01-31'
    ]);
    $response = $this->postJson("/api/v1/payrolls/run/$period->uid");

    $response->assertStatus(403);
});




// Payslips
test('fail on payslips route', function () {
    $response = $this->getJson("/api/v1/payslips/generate");
    $response->assertStatus(403);
});
