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
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payroll extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, DefaultLog, GeneratesUid, TrackUser;

    protected $guarded = ['id', 'uid', 'created_by', 'updated_by', 'deleted_by'];

    protected function casts(): array
    {
        return [
            'is_locked' => 'boolean',
        ];
    }

    public function attendancePeriod(): BelongsTo
    {
        return $this->belongsTo(AttendancePeriod::class);
    }

    public function payslips(): HasMany
    {
        return $this->hasMany(Payslip::class);
    }
}
