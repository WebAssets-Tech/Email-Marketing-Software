<?php

namespace App\Interfaces;

use App\Services\Payment\Base\PaymentGateway;
use App\Services\Payment\Base\PaymentGatewayManager;

/**
 * Resolves PaymentGatewayManager
 *
 * @return PaymentGatewayManager
 */
abstract class PaymentGatewayInterface {
    public function getGateway(string $name): PaymentGateway {
        return new PaymentGateway;
    }

    abstract public function pay();

    abstract public function callback();

    abstract public function webhook();
}
