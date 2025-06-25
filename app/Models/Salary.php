<?php

namespace App\Models;

use App\Traits\GeneratesUid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use HasFactory, GeneratesUid;

    protected $fillable = ['salary'];

    protected function casts(): array
    {
        return [
            'salary' => 'decimal:2'
        ];
    }
}
