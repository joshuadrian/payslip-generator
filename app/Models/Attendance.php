<?php

namespace App\Models;

use App\Traits\Models\TrackUser;
use Spatie\Activitylog\LogOptions;
use App\Traits\Models\GeneratesUid;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use SoftDeletes, LogsActivity, GeneratesUid, TrackUser;

    protected $fillable = ['user_id', 'date', 'attendance_period_id', 'checked_out_at'];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->checked_in_at)) {
                $model->checked_in_at = now();
            }
        });
    }

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'checked_in_at' => 'datetime',
            'checked_out_at' => 'datetime',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function attendancePeriod(): BelongsTo
    {
        return $this->belongsTo(AttendancePeriod::class);
    }
}
