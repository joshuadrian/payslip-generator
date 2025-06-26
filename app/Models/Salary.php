<?php

namespace App\Models;

use App\Traits\Models\GeneratesUid;
use App\Traits\Models\TrackUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Salary extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, GeneratesUid, TrackUser;

    protected $fillable = ['salary'];

    protected function casts(): array
    {
        return [
            'salary' => 'decimal:2',
            'effective_date' => 'date',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }
}
