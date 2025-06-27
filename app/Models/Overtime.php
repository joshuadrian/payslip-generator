<?php

namespace App\Models;

use App\Traits\Models\DefaultLog;
use App\Traits\Models\TrackUser;
use App\Traits\Models\GeneratesUid;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Overtime extends Model
{
    use SoftDeletes, LogsActivity, DefaultLog, GeneratesUid, TrackUser;

    protected $guarded = ['id', 'uid', 'created_by', 'updated_by', 'deleted_by'];

    protected function casts(): array
    {
        return ['date' => 'date'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
