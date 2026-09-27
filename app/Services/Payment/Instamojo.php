<?php

namespace App\Services\Payment;

use App\Contracts\PaymentProcessor;
use App\Http\Requests\Payment\PaymentRequest;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Str;
use Instamojo\Instamojo as InstamojoSDK;
use Throwable;

class Instamojo extends PaymentProcessor {
    /**
     * Instamojo SDK
     *
     * @var InstamojoSDK
     */
    protected $instamojo;


    public function handle(PaymentRequest $request, SubscriptionPlan $subscription): bool {
        try {
            $response = $this->instamojo->getPaymentRequestDetails(request('payment_request_id'));
            dd($response);
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

    public function redirect(PaymentRequest $request, ?SubscriptionPlan $subscription = null) {
        $instamojo = InstamojoSDK::init('app', [
            'client_id' => config('services.payment_gateways.instamojo.client_id'),
            'client_secret' => config('services.payment_gateways.instamojo.client_secret'),
        ], config('services.payment_gateways.instamojo.type'));
        $payment = $instamojo->createPaymentRequest([
            'purpose' => Str::upper($subscription->name).' Plan',
            'amount' => $subscription->amount,
            'send_email' => true,
            'email' => $request->email,
            'phone' => $request->mobile_number,
            'redirect_url' => route('payment.callback', $request->validated()),
        ]);
        if (isset($payment['status']) && $payment['status'] != 'success') {
            $url = route('payment.failed');
        }
        $url = $payment['longurl'];

        return redirect($url);
    }

    public function setShouldRedirect() {
        return true;
    }

    public function getLogoUrl() {
        return filePath('frontend/images/gateway/instamojo.png');
    }

    protected function getPaymentMethod(): string {
        return 'instamojo';
    }
}
