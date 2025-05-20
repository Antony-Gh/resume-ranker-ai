<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class SaverUser extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $table = 'saver_users';

    protected $fillable = [
        'username',
        'email',
        'password',
        'master_password', // For encrypting password entries
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'last_login_at',
        'last_login_ip',
        'last_login_user_agent',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'master_password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'two_factor_confirmed_at' => 'datetime',
        'password' => 'hashed',
        'master_password' => 'hashed',
    ];

    public function passwords()
    {
        return $this->hasMany(SaverPassword::class);
    }

    public function loginHistory()
    {
        return $this->hasMany(SaverLoginHistory::class);
    }

    public function hasVerifiedEmail()
    {
        return !is_null($this->email_verified_at);
    }
}
