<?php

namespace App\Services\Payment;

use App\Contracts\PaymentProcessor;
use App\Http\Requests\Payment\PaymentRequest;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Mollie\Api\MollieApiClient;

class Mollie extends PaymentProcessor {
    protected $mollie;

    public function __construct() {
        parent::__construct();
        $this->mollie = new MollieApiClient();
        $apiKey = config('services.payment_gateways.mollie.api_key');
        if (!empty($apiKey)) {
            try {
                $this->mollie->setApiKey($apiKey);
            } catch (\Throwable $e) {
                // Ignore invalid key during gateway discovery
            }
        }
    }

    public function handle(PaymentRequest $request, SubscriptionPlan $subscription): bool {
        return true;
    }

    public function redirect(PaymentRequest $request, ?SubscriptionPlan $subscription = null) {
        $price = number_format($subscription->price, 2, '.', '');
        $payment = $this->mollie->payments->create([
            'amount' => [
                'currency' => 'USD', // Type of currency you want to send
                'value' => $price, // You must send the correct number of decimals, thus we enforce the use of strings
            ],
            'method'=> 'creditcard',
            'description' => 'Payment for '.$subscription->plan_name.' plan at '. config('app.name'),
            'redirectUrl' => route('payment.success'),
            'webhookUrl' => route('payment.ipn', ['method' => $this->getPaymentMethod()]),
        ]);
        $this->setPaymentRequestSession($request);
        $url = $payment->getCheckoutUrl();

        // redirect customer to Mollie checkout page
        return redirect()->away($url);
    }

    public function ipn(Request $request) {
        if (! $request->has('id')) {
            return false;
        }
        $paymentId = $request->input('id');
        $payment = $this->mollie->payments->get($paymentId);
        if ($payment->isPaid()) {
            $request = $this->getPaymentRequest();
            $subscription = SubscriptionPlan::where('id', $request->plan_id)->first();
            $this->complete($request, $subscription);
            $this->destroyPaymentRequestSession();
        }
    }

    public function setShouldRedirect() {
        return true;
    }

    public function getLogoUrl() {
        return filePath('frontend/images/gateway/mollie.png');
    }

    protected function getPaymentMethod(): string {
        return 'mollie';
    }
}
