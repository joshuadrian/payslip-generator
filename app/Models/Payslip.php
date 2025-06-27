<?php

namespace App\Models;

use App\Traits\Models\DefaultLog;
use App\Traits\Models\TrackUser;
use App\Traits\Models\GeneratesUid;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payslip extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, DefaultLog, GeneratesUid, TrackUser;

    protected $guarded = ['id', 'uid', 'created_by', 'updated_by', 'deleted_by'];

    protected function casts(): array
    {
        return [
            'base_salary' => 'decimal:2',
            'attendance_deduction' => 'decimal:2',
            'overtime_bonus' => 'decimal:2',
            'reimbursement_total' => 'decimal:2',
            'take_home_pay' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payroll(): BelongsTo
    {
        return $this->belongsTo(Payroll::class);
    }
}
