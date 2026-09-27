<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider {
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
        Blade::directive('templateOne', function ($number) {
            return 'ok';
        });
        // HTMX
        Blade::directive('htmx', function (?string $expression = null) {
            $src = empty($expression) ? 'https://unpkg.com/htmx.org@1.9.10' : $expression;

            return "<script src='{$src}' defer></script>";
        });

        //AlpineJs
        Blade::directive('alpineJs', function (?string $expression = null) {
            $src = empty($expression) ? '//unpkg.com/alpinejs' : $expression;

            return "<script src='{$src}' defer></script>";
        });
        //CSS
        Blade::directive('css', function () {
            return '<style type="text/css">';
        });
        Blade::directive('endcss', function () {
            return '</style>';
        });

    }
}
