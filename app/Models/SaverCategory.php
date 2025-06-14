<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 *     schema="SaverCategory",
 *     title="Saver Category",
 *     description="SaverCategory model",
 *     @OA\Property(property="id", type="integer", format="int64", description="ID", readOnly=true),
 *     @OA\Property(property="name", type="string", description="Name of the category"),
 *     @OA\Property(property="icon", type="string", nullable=true, description="Icon for the category"),
 *     @OA\Property(property="color", type="string", nullable=true, description="Color code for the category"),
 *     @OA\Property(property="user_id", type="integer", format="int64", description="User ID of the owner", readOnly=true),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp", readOnly=true),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp", readOnly=true),
 *     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, description="Deletion timestamp", readOnly=true)
 * )
 */
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
