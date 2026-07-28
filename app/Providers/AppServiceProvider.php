<?php

namespace App\Providers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Admin role can do everything — bypasses all other ability checks.
        Gate::before(function (User $user, string $ability) {
            return $user->hasRole(Role::ADMIN) ? true : null;
        });

        Gate::define('manage-users', fn (User $user) => $user->hasRole(Role::USER_MANAGER));
        Gate::define('manage-content', fn (User $user) => $user->hasRole(Role::CONTENT));
        Gate::define('manage-club', fn (User $user) => $user->hasRole(Role::CLUB));
    }
}
