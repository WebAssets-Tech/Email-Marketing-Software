<?php

use App\Http\Controllers\Payment\PaymentController;
use Illuminate\Support\Facades\Route;

Route::prefix('payment')
    ->name('payment.')
    ->controller(PaymentController::class)
    ->group(function () {
        Route::get('/plan/{plan}', 'index')->name('index');
        Route::post('pay', 'pay')->name('pay');
        Route::any('/callback', 'callback')->name('callback');
        Route::any('/ipn', 'ipn')->name('ipn');
        Route::get('success', 'success')->name('success');
        Route::get('failed', 'failed')->name('failed');
        Route::get('cancel', 'cancel')->name('cancel');
    });
