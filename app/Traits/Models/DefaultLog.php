<?php

namespace App\Traits\Models;

use Spatie\Activitylog\LogOptions;

trait DefaultLog
{
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }
}
