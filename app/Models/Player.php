<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Player extends Model
{
    protected $fillable = [
        'team_id', 'first_name', 'last_name', 'name',
        'jersey_number', 'position', 'hand', 'height_cm', 'weight_kg',
        'photo', 'date_of_birth', 'bio', 'sort_order', 'is_active',
    ];

    /** Celé jméno složené z křestního + příjmení. */
    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name) ?: $this->name ?? '';
    }

    protected $casts = [
        'date_of_birth' => 'date',
        'is_active'     => 'boolean',
        'sort_order'    => 'integer',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /** True if today is the player's birthday. */
    public function hasBirthdayToday(): bool
    {
        if (!$this->date_of_birth) return false;
        return $this->date_of_birth->format('m-d') === now()->format('m-d');
    }

    /** Players with birthday today. */
    public static function todaysBirthdays()
    {
        return static::whereNotNull('date_of_birth')
            ->where('is_active', true)
            ->get()
            ->filter(fn($p) => $p->hasBirthdayToday());
    }
}
