<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\AdminPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // User::class => AdminPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Registrar gates de autorização
        Gate::define('isAdmin', function (User $user) {
            return $user->role === 'ADMIN';
        });

        Gate::define('isOwner', function (User $user) {
            return $user->selected_company_role === 'OWNER';
        });

        Gate::define('isManagerOrOwner', function (User $user) {
            return in_array($user->selected_company_role, ['MANAGER', 'OWNER']);
        });
    }
}
