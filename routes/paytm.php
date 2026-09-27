<?php

use App\Http\Controllers\PaytmController;
use Illuminate\Support\Facades\Route;

Route::get('/paytm', [PaytmController::class, 'index'])->name('paytm.index');
Route::get('/paytm/store', [PaytmController::class, 'store'])->name('paytm.store');
Route::post('/paytm/pay', [PaytmController::class, 'redirectToGateway'])->name('paytm.pay');
Route::post('paytm/payment/status', [PaytmController::class, 'paymentCallback'])->name('paytm.callback');

Route::get('/paytm/enabledisable', [PaytmController::class, 'paytmEnableDisable'])
    ->middleware('can:Admin')
    ->name('payment.paytm.enabledisable');
