<?php

namespace App\Traits\Models;

use Illuminate\Support\Str;

trait TrackUser
{
    protected static function bootTrackUser()
    {
        static::creating(function ($model) {
            $model->created_by = auth()->id();
            $model->updated_by = auth()->id();
        });

        static::updating(function ($model) {
            $model->updated_by = auth()->id();
        });

        static::deleting(function ($model)) {
            $model->deleted_by = auth()->id();
        }
    }
}
