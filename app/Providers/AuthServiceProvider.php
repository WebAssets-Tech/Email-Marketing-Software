<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider {
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot() {
        $this->registerPolicies();

        /* define a admin user role */
        Gate::define('Admin', function ($user) {
            return $user->user_type == 'Admin';
        });

        /* define a manager user role */
        Gate::define('Customer', function ($user) {
            return $user->user_type == 'Customer';
        });

        /* define a admin and customer user role */
        Gate::define('Agent', function ($user) {
            return $user->user_type == 'Agent';
        });

        /* define a admin and customer user role */
        Gate::define('AdminCustomer', function ($user) {
            return $user->user_type == 'Admin' || $user->user_type == 'Customer';
        });

        /* define a admin and customer user role */
        Gate::define('AdminAgent', function ($user) {
            return $user->user_type == 'Admin' || $user->user_type == 'Agent';
        });

        /* define a admin and customer user role */
        Gate::define('CustomerAgent', function ($user) {
            return $user->user_type == 'Customer' || $user->user_type == 'Agent';
        });

        /* define a admin and customer and agent user role */
        Gate::define('Everyone', function ($user) {
            return $user->user_type == 'Admin' || $user->user_type == 'Customer' || $user->user_type == 'Agent';
        });

    }
}
