<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaverCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'saver_categories';

    protected $fillable = [
        'user_id',
        'name',
        'icon',
        'color',
    ];

    public function user()
    {
        return $this->belongsTo(SaverUser::class);
    }

    public function passwords()
    {
        return $this->hasMany(SaverPassword::class);
    }
}
