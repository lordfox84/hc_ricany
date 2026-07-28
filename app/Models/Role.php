<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    public const ADMIN        = 'admin';
    public const USER_MANAGER = 'user_manager';
    public const CONTENT      = 'content';
    public const CLUB         = 'club';

    protected $fillable = ['key', 'name', 'description'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
