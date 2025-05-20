<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaverLoginHistory extends Model
{
    use HasFactory;

    protected $table = 'saver_login_history';

    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'location',
        'login_at',
        'logout_at',
        'status',
    ];

    protected $casts = [
        'login_at' => 'datetime',
        'logout_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(SaverUser::class);
    }
}
