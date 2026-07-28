<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublicSkating extends Model
{
    protected $fillable = ['date', 'time_from', 'time_to', 'note', 'is_active'];

    protected $casts = [
        'date'      => 'date',
        'is_active' => 'boolean',
    ];

    /** Upcoming active sessions, soonest first. */
    public function scopeUpcoming($query)
    {
        return $query
            ->where('is_active', true)
            ->where('date', '>=', today())
            ->orderBy('date')
            ->orderBy('time_from');
    }
}
