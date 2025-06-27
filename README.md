# 📘 Payslip Generator

A Laravel-based payroll management system that allows admins to run payrolls and employees to generate detailed payslips, including attendances, reimbursements, and overtime calculations.

----------

## 🛠️ Setup Guide

### Requirements

-   PHP >= 8.2
-   Composer
-   Node.js & NPM
-   PostgreSQL

### Installation

``` console
git clone <repo-url>
cd payslip-generator
cp .env.example .env
composer install
npm install && npm run build
php artisan key:generate
```

### Database Configuration

Edit your `.env` file:

```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=<your_database>
DB_USERNAME=<your_user>
DB_PASSWORD=<your_password>
```

Then run:

``` console
php artisan payslip:migrate {--fresh} {--seed} {--data}
```
> use `--fresh` to run `php artisan migrate:fresh`
> 
> use `--seed` to run `php artisan db:seed`
> 
> use `--data` to run `php artisan db:seed UserDataSeeder`, which will seed Attendances, Reimbursements, and Overtimes data

### Start the App

Serve the app

``` console
php artisan serve
```

Run the worker

``` console
php artisan queue:work
```

----------

## 📡 API Usage

### Authentication

Uses Laravel Sanctum.

-   Obtain a token via login
    
-   Include the token with requests:
    
    ```
    Authorization: Bearer {token}
    ```
    

### Endpoints Overview
> For more information and detailed explanation about API usage, visit `/docs/api`.

#### 🔁 Attendance Period (Admin)

-   `POST /api/v1/auth` – Get authentication token


#### 🔁 Attendance Period (Admin)

-   `GET /api/v1/attendance-periods` – List all attendance periods

-   `POST /api/v1/attendance-periods` – Create a new attendance period

#### ⏱️ Attendance (Employee)

-   `POST /api/v1/attendances/submit` – Check in/out
    

#### ⏫ Overtime / Reimbursement (Employee)

-   `POST /api/v1/overtimes` - Create a new overtime submission
    
-   `POST /api/v1/reimbursements` - Create a new reimbursement submission
    

#### 💸 Payroll (Admin)

-   `GET /api/v1/payrolls` – List all payrolls

-   `POST /api/v1/payrolls/run/{period:uid}` – Run payroll for a period

-   `GET /api/v1/payrolls/{payroll:uid}` – View a specific payroll
    

#### 🧾 Payslip (Employee)

-   `GET /api/v1/payslips/generate` – Generate a payslip
    

#### 📊 Payslip Summary (Admin)

-   `GET /api/v1/payslips/generate/{payroll:uid}` – Generate a payslip summary    

----------

## 🏗️ Software Architecture

### Folder Structure

-   `app/Http/Controllers` – API controllers
    
-   `app/Services` – Domain logic (e.g. `PayrollService`, `PayslipService`)
    
-   `app/Models` – Eloquent models (e.g, `Payslip`, `Payroll`, etc.)
    
-   `app/Jobs` – Background jobs (e.g. `ProcessPayrollChunk`)
    
-   `database/seeders` – Initial role, permission, and setting seeding
    
-   `routes/api.php` – Versioned API routing
    

### Core Concepts

-   **User**: Can be admin or employee
    
-   **AttendancePeriod**: Defines a payroll cycle
    
-   **Attendance**: Employee's check-in/out log
    
-   **Payroll**: One-time execution of salary calculation
    
-   **Payslip**: Contains a breakdown of salary, deductions, etc.
    
-   **Reimbursement & Overtime**: Additional earnings
    

### Async Payroll Processing

-   Uses Laravel's `Bus::batch()` to split payroll processing into chunks.
    
-   Each chunk is processed in a queued job: `ProcessPayrollChunk`.
    
-   Ensures efficient handling of thousands of employees.
