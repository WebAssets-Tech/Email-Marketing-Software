<?php

use App\Http\Controllers\PayPalController;
use App\Http\Controllers\PayPalPaymentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['middleware' => ['installed', 'saas.user.restriction']], function () {
    Route::any('free/payment', [PayPalPaymentController::class, 'freePayment'])
        ->name('freePayment');
    //     Route::any('paypal', [PayPalPaymentController::class, 'postPaymentWithpaypal'])
    // ->name('postPaymentWithpaypal');

    Route::get('paypal', [PayPalPaymentController::class, 'getPaymentStatus'])
        ->name('getPaymentStatus');
    // Route::match(['get','post'],'create-transaction', [PayPalController::class, 'createTransaction'])->name('freePayment');
    Route::post('process-transaction', [PayPalController::class, 'processTransaction'])->name('postPaymentWithpaypal');
    Route::get('success-transaction', [PayPalController::class, 'successTransaction'])->name('successTransaction');
    Route::get('cancel-transaction', [PayPalController::class, 'cancelTransaction'])->name('cancelTransaction');
});
