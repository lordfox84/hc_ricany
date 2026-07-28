<?php

namespace App\Models;

use App\Models\Concerns\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Team extends Model
{
    use Translatable;

    protected $fillable = [
        'name_cs', 'name_en', 'slug', 'age_group',
        'description_cs', 'description_en',
        'color', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saving(function (Team $team) {
            if (empty($team->slug)) {
                $base = Str::slug($team->name_cs);
                $slug = $base;
                $i = 1;
                while (static::where('slug', $slug)->where('id', '!=', $team->id ?? 0)->exists()) {
                    $slug = $base . '-' . $i++;
                }
                $team->slug = $slug;
            }
        });
    }

    public function players(): HasMany
    {
        return $this->hasMany(Player::class)->orderBy('sort_order')->orderBy('name');
    }

    public function activePlayers(): HasMany
    {
        return $this->hasMany(Player::class)->where('is_active', true)->orderBy('last_name')->orderBy('first_name');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
