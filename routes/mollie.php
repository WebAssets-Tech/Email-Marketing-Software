<?php

use App\Http\Controllers\MollieController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['installed', 'saas.user.restriction']], function () {

    Route::get('mollie-payment', [MollieController::class, 'index'])->name('mollie.payment.index');
    Route::post('mollie-payment/setup', [MollieController::class, 'setup'])->name('mollie.payment.setup');
});

Route::post('mollie/make-payment', [MollieController::class, 'preparePayment'])->name('mollie.make.payment');
Route::get('mollie/webhooks', [MollieController::class, 'handleWebhookNotification'])->name('webhooks.mollie');

Route::get('/mollie/enabledisable', [MollieController::class, 'mollieEnableDisable'])
    ->middleware('can:Admin')
    ->name('payment.mollie.enabledisable');
