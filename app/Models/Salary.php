<?php

namespace App\Models;

use App\Traits\Models\GeneratesUid;
use App\Traits\Models\TrackUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Salary extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, GeneratesUid, TrackUser;

    protected $guarded = ['id', 'uid', 'created_by', 'updated_by', 'deleted_by'];

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
