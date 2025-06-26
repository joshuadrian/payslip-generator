<?php

namespace App\Models;

use App\Traits\Models\GeneratesUid;
use App\Traits\Models\TrackUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use SoftDeletes, GeneratesUid, TrackUser;

    protected $fillable = ['user_id', 'date', 'attendance_period_id', 'checked_out_at'];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->checked_in_at = now();
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
}
