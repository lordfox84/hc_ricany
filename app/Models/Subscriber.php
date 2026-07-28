<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $fillable = [
        'email', 'first_name', 'last_name',
        'locale', 'source',
        'gdpr_consent', 'marketing_consent',
        'consented_at', 'unsubscribed_at', 'ip_address',
    ];

    protected $casts = [
        'gdpr_consent'      => 'boolean',
        'marketing_consent' => 'boolean',
        'consented_at'      => 'datetime',
        'unsubscribed_at'   => 'datetime',
    ];

    /** Celé jméno */
    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name) ?: '—';
    }

    /** Aktivně dostává marketing */
    public function getIsActiveAttribute(): bool
    {
        return $this->marketing_consent && $this->unsubscribed_at === null;
    }

    /**
     * Synchronizace z registrace na kemp — volá se po úspěšné registraci
     * s marketing_consent=true. Upsert: pokud e-mail existuje, aktualizuje
     * souhlas, pokud ne, vytvoří nový záznam.
     */
    public static function syncFromRegistration(Registration $reg): void
    {
        $existing = self::where('email', $reg->parent_email)->first();

        if ($existing) {
            // Aktualizovat souhlas a locale
            $existing->marketing_consent = true;
            $existing->unsubscribed_at   = null;   // opět přihlášen
            $existing->locale            = $reg->locale ?? $existing->locale;
            $existing->consented_at      = $existing->consented_at ?? $reg->consented_at ?? now();
            $existing->save();
        } else {
            self::create([
                'email'             => $reg->parent_email,
                'first_name'        => $reg->parent_name,
                'last_name'         => $reg->parent_last_name,
                'locale'            => $reg->locale ?? 'cs',
                'source'            => 'camp',
                'gdpr_consent'      => true,
                'marketing_consent' => true,
                'consented_at'      => $reg->consented_at ?? now(),
                'ip_address'        => $reg->ip_address,
            ]);
        }
    }
}
