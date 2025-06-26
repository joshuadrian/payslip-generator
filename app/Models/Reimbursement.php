<?php

namespace App\Models;

use App\Traits\Models\GeneratesUid;
use App\Traits\Models\TrackUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Reimbursement extends Model
{
    use SoftDeletes, LogsActivity, GeneratesUid, TrackUser;

    protected $fillable = ['user_id', 'amount', 'date', 'description'];

    protected function casts(): array
    {
        return ['date' => 'date'];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }
}
