<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Facades\Storage;

/**
 * @property string $id
 * @property string $user_id
 * @property string $title
 * @property string $author
 * @property string $description
 * @property int $publication_year
 * @property string $slug
 * */
class Book extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $keyType = 'string'; // Since `id` is a `char(36)` UUID
    protected $primaryKey = 'id';
    public $incrementing = false; // UUIDs are not auto-incrementing

    protected $fillable = [
        'user_id', 
        'category_id',  
        'title', 
        'author', 
        'description', 
        'publication_year',
        'cover_image',
        'slug'
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * Get the user that owns the book.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category that owns the Book
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
 
}
