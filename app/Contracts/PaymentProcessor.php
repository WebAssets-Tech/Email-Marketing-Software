<?php

namespace App\Contracts;

use App\Http\Requests\Payment\PaymentRequest;
use App\Jobs\Payment\SendInvoiceJob;
use App\Models\EmailSMSLimitRate;
use App\Models\PlanPurchased;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\UserSentLimitPlan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Throwable;

abstract class PaymentProcessor {
    const PAYMENT_REQUEST_SESSION_KEY = 'payment_request_session';

    public string $successView = 'success.order_success';

    public string $failedView = 'payment.failed';

    public bool $shouldRedirect = false;

    public function __construct() {
        $this->shouldRedirect = $this->setShouldRedirect();
    }

    /**
     * Set should the payment
     *
     * @return bool
     */
    abstract public function setShouldRedirect();

    /**
     * Return Null for no redirection
     *
     * @return string
     */
    abstract public function getLogoUrl();

    /**
     * Undocumented function
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    abstract public function redirect(PaymentRequest $request, SubscriptionPlan $subscription);

    /**
     * handles the payment response
     *
     * @throws Exception
     */
    abstract public function handle(PaymentRequest $request, SubscriptionPlan $subscription): bool;

    /**
     * Handles webhook calls for instant payment notification aka IPN\
     * **This will try to get `PaymentRequest` from session, so remember to `setPaymentRequestSession`\
     * before redirecting**
     */
    public function ipn(Request $request) {

    }

    public function complete(PaymentRequest $request, SubscriptionPlan $subscription): void {
        DB::beginTransaction();
        try {
            $user = checkUser($request->email);
            if ($user == null) {
                $user = new User();
                $user->name = $request->name;
                $user->email = $request->email;
                $user->password = Hash::make($request->password);
                $user->slug = Str::slug($request->name).rand(100, 1000);
                $user->visitor = $_SERVER['REMOTE_ADDR'];
                $user->save();
            }
            $plan = $this->createPlan($subscription, $user, $this->getPaymentMethod());
            $this->createLimit($subscription, $user);

            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        SendInvoiceJob::dispatch($plan, $user);
    }

    /**
     * Set the `PaymentRequest` to session using the key`$this::PAYMENT_REQUEST_SESSION_KEY`
     * @param \App\Http\Requests\Payment\PaymentRequest $request
     * @return void
     */
    public function setPaymentRequestSession(PaymentRequest $request) {
        session()->put($this::PAYMENT_REQUEST_SESSION_KEY, $request->validated());
    }

    /**
     * Retrieves the `PaymentRequest` from session
     *
     * @return \App\Http\Requests\Payment\PaymentRequest
     */
    public function getPaymentRequest() {
        $requestData = session($this::PAYMENT_REQUEST_SESSION_KEY);
        $request = new PaymentRequest(request: $requestData ?? []);

        return $request;
    }

    /**
     * Destroys the `PaymentRequest from session`
     *
     * @return void
     */
    public function destroyPaymentRequestSession() {
        return session()->forget($this::PAYMENT_REQUEST_SESSION_KEY);
    }

    abstract protected function getPaymentMethod(): string;

    protected function createPlan(SubscriptionPlan $subscription, User $user, string $gateway): PlanPurchased {
        $plan = new PlanPurchased();
        $plan->user_id = $user->id;
        $plan->plan_id = $subscription->id;
        $plan->plan_name = $subscription->name;
        $plan->price = $subscription->price;
        $plan->invoice = invoiceNumber();
        $plan->gateway = $gateway;
        $plan->status = true;
        $plan->save();

        return $plan;
    }

    protected function createLimit(SubscriptionPlan $subscription, User $user): void {
        $new_limit = new UserSentLimitPlan();
        $new_limit->owner_id = $user->id;
        $new_limit->plan_name = $subscription->name;
        $new_limit->limit = $subscription->emails;
        $new_limit->from = now();
        $new_limit->to = now()->addMonths($subscription->duration);
        $new_limit->status = true;
        $new_limit->save();

        $email_sms_rate = new EmailSMSLimitRate();
        $email_sms_rate->owner_id = $user->id;
        $email_sms_rate->email = $subscription->emails;
        $email_sms_rate->sms = $subscription->sms;
        $email_sms_rate->from = now();
        $email_sms_rate->to = now()->addMonths($subscription->duration);
        $email_sms_rate->status = true;
        $email_sms_rate->save();
    }
}
