<?php

namespace App\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        view()->composer('*', function ($view) {
            $view->with('user', auth()->user());
        });

        Schema::defaultStringLength(191);

        // CollectionMacros
        Collection::macro('toUpper', function () {
            return $this->map(function ($value) {
                return is_string($value) ? Str::upper($value) : $value;
            });
        });
        Collection::macro('toLower', function () {
            return $this->map(function ($value) {
                return is_string($value) ? Str::lower($value) : $value;
            });
        });
        Collection::macro('find', function ($key) {
            $filteredArray = $this->filter(function ($value, $k) use ($key) {
                return $key == $k;
            });

            return $filteredArray[$key];
        });
        Collection::macro('recursive', function () {
            $map = $this->map(function ($value, $key) {
                if (is_array($value)) {
                    return collect($value)->recursive();
                }

                return $value;

            });

            return collect($map);
        });
    }
}
