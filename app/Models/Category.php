<?php

namespace App\Models;

use App\Models\Concerns\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    use Translatable;

    protected $fillable = ['name_cs', 'name_en', 'slug', 'color'];

    protected static function booted(): void
    {
        static::saving(function (Category $cat) {
            if (empty($cat->slug)) {
                $cat->slug = Str::slug($cat->name_cs);
            }
        });
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    /** Vrátí hex barvu nebo fallback */
    public function colorHex(): string
    {
        return $this->color ?: '#2abfbf';
    }
}
