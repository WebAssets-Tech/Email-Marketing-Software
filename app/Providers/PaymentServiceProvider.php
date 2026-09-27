<?php

namespace App\Providers;

use App\Contracts\PaymentProcessor;
use Exception;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider {
    /**
     * Register services.
     *
     * @return void
     */
    public function register() {
        /**
         * @var string|null $method payment method from request
         */
        $method = request()->method;
        if ($method) {
            $method = str($method)->studly();
            $class = "App\\Services\\Payment\\{$method}";
            if (class_exists($class)) {
                $this->app->bind(PaymentProcessor::class, $class);
            } else {
                throw new Exception("Service not found for payment method $method in  app/Services/Payment directory.");
            }
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot() {

    }
}
