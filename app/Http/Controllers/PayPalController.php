<?php

namespace App\Http\Controllers;

use App\Mail\InvoiceMail;
use App\Models\EmailSMSLimitRate;
use App\Models\PlanPurchased;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\UserSentLimitPlan;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;
use Session;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PayPalController extends Controller {
    /**
     * create transaction.
     *
     * @return \Illuminate\Http\Response
     */
    public function createTransaction() {
        return view('transaction');
    }

    /**
     * process transaction.
     */
    public function processTransaction(Request $request) {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();
        $plan_details = SubscriptionPlan::where('id', $request->subscriptoin_plan_id)->first();

        $response = $provider->createOrder([
            'intent' => 'CAPTURE',
            'application_context' => [
                'return_url' => route('successTransaction'),
                'cancel_url' => route('cancelTransaction'),
            ],
            'purchase_units' => [
                0 => [
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => $plan_details->price,
                    ],
                ],
            ],
        ]);
        if (! isset($response['id']) || $response['id'] == null) {
            throw new Exception(json_encode($response), 1);
        }
        if (Auth::check()) {
            $user = auth()->user();
        } else {
            if (! checkUser($request->email) == null) {
                Alert::error(@translate('Whoops'), @translate('User Already Exist, Please Login and Retry'));

                return back();
            }
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->slug = Str::slug($request->name).rand(100, 1000);
            $user->visitor = $_SERVER['REMOTE_ADDR'];
            $user->save();
            $request->session()->forget('new_user_id');
            $request->session()->put('new_user_id', $user->id);
        }

        /**
         * PURCHASE CHECK
         */
        $plan = new PlanPurchased();
        $plan->user_id = $user->id;
        $plan->plan_id = $request->subscriptoin_plan_id;
        $plan->plan_name = $request->plan_name;
        $plan->invoice = invoiceNumber();
        $plan->price = $request->amount;
        $plan->gateway = $request->payment_type;
        $plan->status = false;
        $plan->save();

        $new_limit = new UserSentLimitPlan();
        $new_limit->owner_id = $user->id;
        $new_limit->plan_name = $plan_details->name;
        $new_limit->limit = $plan_details->emails;
        $new_limit->from = Carbon::now();
        $new_limit->to = Carbon::now()->addMonths($plan_details->duration);
        $new_limit->status = false;
        $new_limit->save();

        /**
         * EMAIL SMS LIMIT RATE
        //  */
        $email_sms_rate = EmailSMSLimitRate::updateOrCreate([
            'owner_id' => $user->id,
        ],
            [
                'owner_id' => $user->id,
                'email' => $plan_details->emails,
                'sms' => $plan_details->sms,
                'from' => Carbon::now(),
                'to' => Carbon::now()->addMonths($plan_details->duration),
                'status' => false,
            ]);
        foreach ($response['links'] as $links) {
            if ($links['rel'] == 'approve') {
                return redirect()->away($links['href']);
            }
        }

    }

    /**
     * success transaction.
     */
    public function successTransaction(Request $request) {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request['token']);
        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            Session::put('success', 'Payment success !!');

            $userId = auth()->user() ? auth()->id() : $request->session()->get('new_user_id');
            /**
             * PURCHASE CHECK
             */
            $success_plan = PlanPurchased::where('user_id', $userId)->latest()->first();
            $success_plan->status = true;
            $success_plan->save();

            $plan_details = SubscriptionPlan::where('id', $success_plan->plan_id)->first();

            $success_limit = UserSentLimitPlan::where('owner_id', $userId)->first();
            $success_limit->status = true;
            $success_limit->save();

            /**
             * EMAIL SMS LIMIT RATE
             */
            $update_email_sms_rate = EmailSMSLimitRate::where('owner_id', $userId)->first();
            $update_email_sms_rate->status = true;
            $update_email_sms_rate->save();
            Pdf::loadView(
                'success.attachment_invoice', ['details' => $success_plan]
            )->save(invoice_path($success_plan->invoice));
            Mail::to(getUser($success_plan->user_id)->email)->send(new InvoiceMail($success_plan));

            return view('success.order_success');
        } else {
            Session::put('error', 'Payment failed !!');

            return view('errors.payment_failed');
        }
    }
}
