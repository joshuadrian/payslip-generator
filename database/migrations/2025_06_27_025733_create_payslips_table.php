<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payslips', function (Blueprint $table) {
            $table->id();
            $table->string('uid', 7);
            $table->foreignId('user_id')->constrained();
            $table->foreignId('payroll_id')->constrained();
            $table->decimal('base_salary', 15, 2);
            $table->decimal('total_reimbursement', 15, 2)->default(0);
            $table->decimal('total_overtime_hours', 6, 2);
            $table->decimal('overtime_bonus', 15, 2)->default(0);
            $table->decimal('absence_deduction', 15, 2)->default(0);
            $table->decimal('take_home_pay', 15, 2);
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->foreignId('deleted_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payslips');
    }
};
