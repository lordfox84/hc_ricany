<?php

namespace App\Models;

use App\Models\Concerns\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    use Translatable;

    protected $fillable = [
        'title_cs', 'title_en',
        'excerpt_cs', 'excerpt_en',
        'body_cs', 'body_en',
        'category_cs', 'category_en',
        'category_id',
        'image',
        'is_featured', 'is_published',
        'user_id', 'published_at',
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

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
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

    /**
     * Published articles that also have a complete translation for the given
     * locale. Czech is the base language and is always considered complete;
     * other locales require excerpt_{locale} and body_{locale} to be filled in.
     */
    public function scopePublishedFor($query, string $locale)
    {
        $query = $query->published();

        if (in_array($locale, ['en'], true)) {
            $query->whereNotNull("excerpt_{$locale}")->where("excerpt_{$locale}", '!=', '')
                  ->whereNotNull("body_{$locale}")->where("body_{$locale}", '!=', '');
        }

        return $query;
    }

    /** Whether this article has a complete translation for the given locale. */
    public function isCompleteFor(string $locale): bool
    {
        if ($locale === 'cs') {
            return true;
        }

        return filled($this->{"excerpt_{$locale}"}) && filled($this->{"body_{$locale}"});
    }
}
