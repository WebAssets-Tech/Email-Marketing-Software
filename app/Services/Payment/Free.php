<?php

namespace App\Services\Payment;

use App\Contracts\PaymentProcessor;
use App\Http\Requests\Payment\PaymentRequest;
use App\Models\SubscriptionPlan;

class Free extends PaymentProcessor {
    public string $successView = 'success.order_success';

    public string $failedView = 'payment.failed';

    public function handle(PaymentRequest $request, SubscriptionPlan $subscription): bool {
        $this->complete($request, $subscription);

        return true;
    }

    public function redirect(PaymentRequest $request, ?SubscriptionPlan $subscription = null) {

    }

    public function setShouldRedirect() {
        return false;
    }

    protected function getPaymentMethod(): string {
        return 'free';
    }
    public function getLogoUrl()
    {
        return filePath("frontend/images/gateway/free.png");
    }
}
