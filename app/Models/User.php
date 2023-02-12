<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable {
    use HasApiTokens, HasFactory, Notifiable, Filterable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'avatar',
        'firstname',
        'lastname',
        'email',
        'age',
        'birthday',
        'gender',
        'about_me',
        'language',
        'provider_id',
        'wallet',
        'rating',
        'read_school_exp',
        'rate_count',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function ratings() {
        return $this->hasMany(RatingLog::class);
    }

    public function modelFilter() {
        return $this->provideFilter(\App\ModelFilters\UserFilter::class);
    }

    public function reports() {
        return $this->belongsToMany(ReportType::class, 'report_logs', 'profile_id', 'id');
    }
}
