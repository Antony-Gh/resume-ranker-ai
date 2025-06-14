<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @OA\Schema(
 *     schema="SaverUser",
 *     title="Saver User",
 *     description="SaverUser model",
 *     @OA\Property(property="id", type="integer", format="int64", description="ID", readOnly=true),
 *     @OA\Property(property="username", type="string", description="Username"),
 *     @OA\Property(property="email", type="string", format="email", description="User's email address"),
 *     @OA\Property(property="role", type="string", description="User role (e.g., user, admin)", nullable=true, readOnly=true),
 *     @OA\Property(property="email_verified_at", type="string", format="date-time", nullable=true, description="Timestamp of email verification", readOnly=true),
 *     @OA\Property(property="last_login_at", type="string", format="date-time", nullable=true, description="Timestamp of last login", readOnly=true),
 *     @OA\Property(property="last_login_ip", type="string", nullable=true, description="IP address of last login", readOnly=true),
 *     @OA\Property(property="last_login_user_agent", type="string", nullable=true, description="User agent of last login", readOnly=true),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp", readOnly=true),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp", readOnly=true)
 * )
 */
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
