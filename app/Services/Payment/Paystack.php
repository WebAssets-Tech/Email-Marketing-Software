<?php

namespace App\Services\Payment;

use App\Contracts\PaymentProcessor;
use App\Http\Requests\Payment\PaymentRequest;
use App\Models\Currency;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Session;
use Throwable;
use Unicodeveloper\Paystack\Facades\Paystack as PaystackSDK;

class Paystack extends PaymentProcessor
{
    public function handle(PaymentRequest $request, SubscriptionPlan $subscription): bool
    {
        try {
            $response = PaystackSDK::getPaymentData();
            if (! isset($response['status'])) {
                return false;
            }
            if ($response['status'] != true) {
                return false;
            }
            session('success', 'Payment success !!');
            $this->complete($request, $subscription);

            return true;
        } catch (Throwable $th) {
            throw $th;
        }
    }

    public function redirect(PaymentRequest $request, ?SubscriptionPlan $subscription = null)
    {
        Session::flash('currency', Currency::where('code', env('MERCHANT_CURRENCY'))->first()->id);

        request()->request->add([
            'amount' => convertPrice($subscription->price * 100),
            'first_name' => $request->name,
            'callback_url' => route('payment.callback', $request->validated()),
            'currency' => env('MERCHANT_CURRENCY'),
            'quantity' => 1,
        ]);

        return PaystackSDK::getAuthorizationUrl()->redirectNow();
    }

    public function setShouldRedirect()
    {
        return true;
    }

    public function getLogoUrl()
    {
        return filePath('frontend/images/gateway/paystack.png');
    }

    protected function getPaymentMethod(): string
    {
        return 'paystack';
    }
}
