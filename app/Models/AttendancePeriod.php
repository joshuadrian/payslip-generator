<?php

namespace App\Models;

use App\Traits\Models\GeneratesUid;
use App\Traits\Models\TrackUser;
use Illuminate\Database\Eloquent\Model;

class AttendancePeriod extends Model
{
    use GeneratesUid, TrackUser;

    protected $fillable = ['start_date', 'end_date'];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'is_locked' => 'boolean',
        ];
    }
}
