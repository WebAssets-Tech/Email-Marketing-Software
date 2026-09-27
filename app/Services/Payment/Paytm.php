<?php

namespace App\Services\Payment;

use App\Models\SubscriptionPlan;
use App\Contracts\PaymentProcessor;
use App\Http\Requests\Payment\PaymentRequest;
use Anand\LaravelPaytmWallet\Facades\PaytmWallet;

class Paytm extends PaymentProcessor {
    public function handle(PaymentRequest $request, SubscriptionPlan $subscription): bool {
        $orderId = session('paytm-order-id');
        $status = PaytmWallet::with('status');
        $status->prepare(['order' => $orderId]);
        $status->check();

        //Remove order Id from session
        session()->forget('paytm-order-id');

        $response = $status->response();
        if($status->isFailed()) {
            return false;
        }
        $this->complete($request, $subscription);
        return true;
    }

    public function redirect(PaymentRequest $request, ?SubscriptionPlan $subscription = null) {
        $payment = PaytmWallet::with('receive');
        $orderId = uniqid('order-');
        session()->put('paytm-order-id', $orderId);
        $payment->prepare([
            'order' => $orderId,
            'user' => $request->name,
            'mobile_number' => 917428730894,
            'email' => $request->email,
            'amount' => $subscription->price,
            'callback_url' => route('payment.callback', $request->validated()),
        ]);
        return $payment->receive();
    }

    public function setShouldRedirect() {
        return true;
    }
    public function getLogoUrl() {
        return filePath("frontend/images/gateway/paytm.png");
    }

    protected function getPaymentMethod(): string {
        return 'paytm';
    }
}
