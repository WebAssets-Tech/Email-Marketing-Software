<?php

namespace App\Services\Payment;

use App\Models\SubscriptionPlan;
use App\Contracts\PaymentProcessor;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\Payment\PaymentRequest;

class Khalti extends PaymentProcessor {
    protected $khalti;
    public function __construct() {
        parent::__construct();
        /**
         * @var string $url Khalti BaseUrl
         */
        $url = "https://khalti.com/api/v2";

        //Change to sandbox url if sandbox is enabled
        if (config('services.payment_gateways.khalti.is_sandbox')) {
            $url = "https://a.khalti.com/api/v2";
        }

        $this->khalti = Http::withHeaders([
            "Authorization" => "Key ".config('services.payment_gateways.khalti.secret'),
        ])->baseUrl($url);
    }


    public function handle(PaymentRequest $request, SubscriptionPlan $subscription): bool {
        $isSuccess = $request->status == 'Completed';
        if ($isSuccess) {
            $this->complete($request, $subscription);
        }
        return $isSuccess;
    }

    public function redirect(PaymentRequest $request, SubscriptionPlan $subscription) {
        $orderId = uniqid('maildoll-order-');
        $response = $this->khalti->post('/epayment/initiate/', [
            'return_url' => route('payment.callback', $request->validated()),
            'website_url' => url(),
            'amount' => $subscription->price * 100,
            'purchase_order_id' => $orderId,
            'purchase_order_name' => $subscription->name,
            'customer_info' => [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone
            ],
        ]);
        dd($response->body());
    }

    public function setShouldRedirect() {
        return true;
    }

    public function getLogoUrl() {
        return filePath('frontend/images/gateway/khalti.png');
    }

    protected function getPaymentMethod(): string {
        return 'khalti';
    }
}
