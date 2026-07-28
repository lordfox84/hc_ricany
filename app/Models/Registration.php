<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Registration extends Model
{
    protected $fillable = [
        'camp_id',
        'parent_name', 'parent_last_name', 'parent_email', 'parent_phone',
        'child_name', 'child_last_name', 'child_date_of_birth',
        'note',
        'street', 'city', 'zip', 'country',
        'club', 'position', 'jersey_size', 'jersey_size_custom', 'variant',
        'gdpr_consent', 'marketing_consent',
        'consented_at', 'ip_address', 'locale',
    ];

    protected $casts = [
        'gdpr_consent'       => 'boolean',
        'marketing_consent'  => 'boolean',
        'consented_at'       => 'datetime',
        'child_date_of_birth'=> 'date',
    ];

    /** Celé jméno rodiče */
    public function getParentFullNameAttribute(): string
    {
        return trim($this->parent_name . ' ' . $this->parent_last_name);
    }

    /** Celé jméno hráče */
    public function getChildFullNameAttribute(): string
    {
        return trim($this->child_name . ' ' . $this->child_last_name);
    }

    /** Velikost dresu – zobrazovací hodnota */
    public function getJerseySizeDisplayAttribute(): string
    {
        if ($this->jersey_size === 'jiná') {
            return $this->jersey_size_custom ?: 'jiná';
        }
        return $this->jersey_size ?? '';
    }

    public function camp(): BelongsTo
    {
        return $this->belongsTo(Camp::class);
    }
}
