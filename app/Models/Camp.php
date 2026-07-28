<?php

namespace App\Models;

use App\Models\Concerns\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Camp extends Model
{
    use Translatable;

    protected $fillable = [
        'user_id', 'type',
        'title_cs', 'title_en',
        'excerpt_cs', 'excerpt_en',
        'body_cs', 'body_en',
        'poster', 'date_from', 'date_to',
        'is_published', 'published_at',
        'registration_open', 'capacity', 'variants',
    ];

    protected $casts = [
        'is_published'      => 'boolean',
        'registration_open' => 'boolean',
        'published_at'      => 'datetime',
        'date_from'         => 'date',
        'date_to'           => 'date',
        'variants'          => 'array',
    ];

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public function spotsLeft(): ?int
    {
        if (!$this->capacity) return null;
        return max(0, $this->capacity - $this->registrations()->count());
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)->latest('published_at');
    }
}
