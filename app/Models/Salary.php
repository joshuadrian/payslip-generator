<?php

namespace App\Models;

use App\Traits\Models\GeneratesUid;
use App\Traits\Models\TrackUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use HasFactory, GeneratesUid, TrackUser;

    protected $fillable = ['salary'];

    protected function casts(): array
    {
        return [
            'salary' => 'decimal:2'
        ];
    }
}
