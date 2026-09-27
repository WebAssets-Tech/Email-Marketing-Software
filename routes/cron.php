<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::prefix('cron')
    ->name('cron')
    ->group(function () {
        Route::get('queue', function () {
            return Artisan::call('queue:work --stop-when-empty');
        });
        Route::get('schedule', function () {
            return Artisan::call('schedule:run');
        });
        Route::get('queue-retry', function () {
            return Artisan::call('queue:retry all');
        });
    });
