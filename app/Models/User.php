<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\Models\DefaultLog;
use App\Traits\Models\GeneratesUid;
use App\Traits\Models\TrackUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, SoftDeletes, Notifiable, HasRoles, LogsActivity, CausesActivity, DefaultLog, GeneratesUid, TrackUser;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function salaries(): HasMany
    {
        return $this->hasMany(Salary::class);
    }

    public function latestSalary(): HasOne
    {
        return $this->hasOne(Salary::class)->latestOfMany('effective_date');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function reimbursements(): HasMany
    {
        return $this->hasMany(Reimbursement::class);
    }

    public function totalReimbursement(): HasOne
    {
        return $this->hasOne(Reimbursement::class)
            ->selectRaw('user_id, sum(amount) as total')
            ->groupBy('user_id');
    }

    public function overtimes(): HasMany
    {
        return $this->hasMany(Overtime::class);
    }

    public function totalOvertime(): HasOne
    {
        return $this->hasOne(Overtime::class)
            ->selectRaw('user_id, sum(duration_hours) as total')
            ->groupBy('user_id');
    }

    public function payslips(): HasMany
    {
        return $this->hasMany(Payslip::class);
    }

    public function scopeEmployee(Builder $query)
    {
        return $query->role('Employee');
    }

    public function scopeAdmin(Builder $query)
    {
        return $query->role('Admin');
    }
}
