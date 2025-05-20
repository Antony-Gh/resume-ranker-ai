<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class SaverPassword extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'saver_passwords';

    protected $fillable = [
        'user_id',
        'title',
        'username',
        'encrypted_password',
        'category_id',
        'website_url',
        'notes',
        'favorite',
        'tags',
    ];

    protected $hidden = [
        'encrypted_password',
    ];

    protected $casts = [
        'favorite' => 'boolean',
        'tags' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(SaverUser::class);
    }

    public function category()
    {
        return $this->belongsTo(SaverCategory::class);
    }

    protected function password(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => decrypt($value),
            set: fn ($value) => encrypt($value),
        );
    }
}
