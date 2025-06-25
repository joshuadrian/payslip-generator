<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait GeneratesUid
{
    protected static function bootGeneratesUid()
    {
        static::creating(function ($model) {
            $model->uid = Str::random(7);
        });
    }
}
