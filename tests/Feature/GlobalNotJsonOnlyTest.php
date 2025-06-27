<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('fail on auth route', function () {
    $response = $this->post('/api/v1/auth');
    $response->assertStatus(406);
});

test('fail on attendance periods route', function () {
    $response = $this->post('/api/v1/attendance-periods');
    $response->assertStatus(406);
});

test('fail on attendances route', function () {
    $response = $this->post("/api/v1/attendances/submit");
    $response->assertStatus(406);
});

test('fail on overtimes routes', function () {
    $response = $this->post("/api/v1/overtimes");
    $response->assertStatus(406);
});

test('fail on reimbursements routes', function () {
    $response = $this->post("/api/v1/reimbursements");
    $response->assertStatus(406);
});

test('fail on payrolls index routes', function () {
    $response = $this->get("/api/v1/payrolls");
    $response->assertStatus(406);
});

test('fail on payrolls show routes', function () {
    $response = $this->get("/api/v1/payrolls/wrong");
    $response->assertStatus(406);
});

test('fail on payrolls store routes', function () {
    $response = $this->post("/api/v1/payrolls/run/wrong");
    $response->assertStatus(406);
});
