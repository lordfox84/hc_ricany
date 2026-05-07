<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    protected $fillable = [
        'title_cs',
        'title_en',
        'excerpt_cs',
        'excerpt_en',
        'body_cs',
        'body_en',
        'category_cs',
        'category_en',
        'image',
        'is_featured',
        'is_published',
        'user_id',
        'published_at',
    ];

    protected $casts = [
        'is_featured'  => 'boolean',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** Ensure only one article is featured at a time. */
    public static function setFeatured(int $id): void
    {
        static::where('is_featured', true)->update(['is_featured' => false]);
        static::where('id', $id)->update(['is_featured' => true]);
    }

    /** Published articles, newest first. */
    public function scopePublished($query)
    {
        return $query->where('is_published', true)->latest('published_at');
    }
}
