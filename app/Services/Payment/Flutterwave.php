<?php

namespace App\Services\Payment;

use App\Contracts\PaymentProcessor;
use App\Http\Requests\Payment\PaymentRequest;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Str;
use KingFlamez\Rave\Facades\Rave as FlutterwaveSDK;
use Throwable;

class Flutterwave extends PaymentProcessor
{
    public function handle(PaymentRequest $request, SubscriptionPlan $subscription): bool
    {
        try {
            $transactionID = FlutterwaveSDK::getTransactionIDFromCallback();
            $response = FlutterwaveSDK::verifyTransaction($transactionID);
            if (! isset($response['status'])) {
                return false;
            }
            if ($response['status'] != 'success') {
                return false;
            }

            session('success', 'Payment success !!');
            $this->complete($request, $subscription);

            return true;
        } catch (Throwable $th) {
            throw $th;
        }
    }

    public function redirect($request, $subscription = null)
    {
        $reference = FlutterwaveSDK::generateReference();
        $data = [
            'payment_options' => 'card,banktransfer',
            'amount' => $subscription->price,
            'email' => $request->email,
            'tx_ref' => $reference,
            'currency' => env('FLW_CURRENCY', 'NGN'),
            'redirect_url' => route('payment.callback', $request->validated()),
            'customer' => [
                'email' => $request->email,
                'phone_number' => '1234567890',
                'name' => $request->name,
            ],

            'customizations' => [
                'title' => Str::upper($subscription->name) . ' Plan',
                'description' => $subscription->description . ' on ' . now()->toDateTimeString(),
            ],
        ];

        $payment = FlutterwaveSDK::initializePayment($data);
        if (isset($payment['status']) && $payment['status'] != 'success') {
            $url = route('payment.failed');
        }
        $url = $payment['data']['link'];

        return redirect($url);
    }

    public function setShouldRedirect()
    {
        return true;
    }

    public function getLogoUrl()
    {
        return filePath('frontend/images/gateway/flutterwave.png');
    }

    protected function getPaymentMethod(): string
    {
        return 'flutterwave';
    }
}
