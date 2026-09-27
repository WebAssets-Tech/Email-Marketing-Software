<?php

namespace App\Providers;

use App\Models\Moniz;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider {
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
        View::composer('*', 'App\Http\View\Composers\FakerComposer');
        View::composer('*', 'App\Http\View\Composers\LoggedInUserComposer');

        if (env('ACTIVE_THEME') == 'moniz') {
            $this->app->singletonIf('moniz', function () {
                return Moniz::first();
            });
            // dd(app('moniz'));
            View::composer([
                'frontend.moniz.*',
            ], function ($view) {
                $view->with('moniz', app('moniz'));
            });
        }
    }
}
