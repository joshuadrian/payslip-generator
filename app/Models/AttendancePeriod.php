<?php

namespace App\Models;

use App\Models\Scopes\UnlockedScope;
use App\Traits\Models\GeneratesUid;
use App\Traits\Models\TrackUser;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ScopedBy(UnlockedScope::class)]
class AttendancePeriod extends Model
{
    use SoftDeletes, GeneratesUid, TrackUser;

    protected $fillable = ['start_date', 'end_date'];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'is_locked' => 'boolean',
        ];
    }

    public function scopeWithLocked(Builder $query)
    {
        $query->withoutGlobalScope(UnlockedScope::class);
    }
}
