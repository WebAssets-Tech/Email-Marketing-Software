<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class TimezoneServiceProvider extends ServiceProvider {
    /**
     * Register services.
     *
     * @return void
     */
    public function register() {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot() {
        view()->composer('*', function ($view) {
            $user = auth()->user();
            if ($user !== null && $user->timezone !== null) {
                date_default_timezone_set($user->timezone);
            }
        });
    }
}
