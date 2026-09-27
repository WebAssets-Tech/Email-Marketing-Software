<?php

namespace App\Services\Payment;

use App\Contracts\PaymentProcessor;
use App\Http\Requests\Payment\PaymentRequest;
use App\Models\SubscriptionPlan;
use Exception;
use Srmklive\PayPal\Services\PayPal as PaypalSdk;
use Throwable;

class Paypal extends PaymentProcessor
{
    public function handle(PaymentRequest $request, SubscriptionPlan $subscription): bool
    {
        try {
            $provider = new PaypalSdk;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();
            $response = $provider->capturePaymentOrder($request['token']);
            $isSuccess = $response['status'] == 'COMPLETED';
            if (! $isSuccess) {
                throw new Exception('Something went wrong when capturing paypal payment');
            }
            $this->complete($request, $subscription);

            return true;
        } catch (Throwable $th) {
            throw $th;
        }
    }

    public function redirect(PaymentRequest $request, SubscriptionPlan $subscription)
    {
        $provider = null;
        try {
            $provider = new PaypalSdk;
            $provider->setApiCredentials(config('paypal'));
            $paypalToken = $provider->getAccessToken();
        } catch (\Throwable $th) {
            $mode = config('paypal.mode');
            $message = "Invalid paypal credentials for $mode mode";
            throw new Exception($message);
        }
        $response = $provider->createOrder([
            'intent' => 'CAPTURE',
            'application_context' => [
                'return_url' => route('payment.callback', $request->validated()),
                'cancel_url' => route('payment.cancel'),
            ],
            'purchase_units' => [
                0 => [
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => $subscription->price,
                    ],
                ],
            ],
        ]);
        if (!isset($response['links'])) {
            throw new Exception('Login to your paypal account and try again');
        }
        $link = collect($response['links'])->where('rel', 'approve')->first();

        return redirect()->away($link['href']);
    }

    public function setShouldRedirect()
    {
        return true;
    }

    public function getLogoUrl()
    {
        return filePath('frontend/images/gateway/paypal.png');
    }

    protected function getPaymentMethod(): string
    {
        return 'paypal';
    }
}
