<?php

namespace App\Models;

use App\Traits\Models\TrackUser;
use Spatie\Activitylog\LogOptions;
use App\Traits\Models\GeneratesUid;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Overtime extends Model
{
    use SoftDeletes, LogsActivity, GeneratesUid, TrackUser;

    protected $fillable = ['user_id', 'date', 'duration_hours'];

    protected function casts(): array
    {
        return ['date' => 'date'];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
