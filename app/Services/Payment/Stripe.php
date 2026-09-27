<?php

namespace App\Services\Payment;

use App\Contracts\PaymentProcessor;
use App\Http\Requests\Payment\PaymentRequest;
use App\Models\SubscriptionPlan;
use Throwable;

class Stripe extends PaymentProcessor {
    public function handle(PaymentRequest $request, SubscriptionPlan $subscription): bool {
        try {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            \Stripe\Charge::create([
                'amount' => 100 * $subscription->price,
                'currency' => 'usd',
                'source' => $request->source,
                'description' => orgName().' '.$subscription->name.' subscription plan payment',
            ]);
            $this->complete($request, $subscription);

            return true;
        } catch (Throwable $th) {
            throw $th;
        }
    }

    public function redirect(PaymentRequest $request, ?SubscriptionPlan $subscription = null) {
        return redirect()->route('');
    }

    public function setShouldRedirect() {
        return false;
    }

    protected function getPaymentMethod(): string {
        return 'stripe';
    }
    public function getLogoUrl()
    {
        return filePath("frontend/images/gateway/stripe.png");
    }
}
