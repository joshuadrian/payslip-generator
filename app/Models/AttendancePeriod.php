<?php

namespace App\Models;

use App\Models\Scopes\UnlockedScope;
use App\Traits\Models\DefaultLog;
use App\Traits\Models\GeneratesUid;
use App\Traits\Models\TrackUser;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

// #[ScopedBy(UnlockedScope::class)]
class AttendancePeriod extends Model
{
    use SoftDeletes, LogsActivity, DefaultLog, GeneratesUid, TrackUser;

    protected $guarded = ['id', 'uid', 'created_by', 'updated_by', 'deleted_by'];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'is_locked' => 'boolean',
        ];
    }

    public function scopeUnlocked(Builder $query)
    {
        $query->where('is_locked', false);
    }

    // public function scopeWithLocked(Builder $query)
    // {
    //     $query->withoutGlobalScope(UnlockedScope::class);
    // }
}
