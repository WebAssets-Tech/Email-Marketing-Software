<?php

use App\Http\Controllers\RazorpayController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['installed', 'saas.user.restriction']], function () {

    Route::get('razorpay-payment', [RazorpayController::class, 'index'])->name('razorpay.payment.index');
    Route::post('razorpay-payment/setup', [RazorpayController::class, 'setup'])->name('razorpay.payment.setup');
});

Route::get('razorpay-payment/gateway', [RazorpayController::class, 'gateway'])->name('razorpay.payment.gateway');
Route::post('razorpay-make-payment', [RazorpayController::class, 'payment'])->name('razorpay.payment.payment');

Route::get('/razorpay/enabledisable', [RazorpayController::class, 'razorpayEnableDisable'])
    ->middleware('can:Admin')
    ->name('payment.razorpay.enabledisable');
