<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * @property string $id
 * @property string $name
 * @property string $description
 * @property string $slug
 * */
class Category extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $keyType = 'string';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable = [
        'name', 'description', 'slug'
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * The books that belong to the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
