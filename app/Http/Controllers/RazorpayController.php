<?php

namespace App\Http\Controllers;

use Alert;
use App\Mail\InvoiceMail;
use App\Models\Demo;
use App\Models\EmailSMSLimitRate;
use App\Models\PlanPurchased;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\UserSentLimitPlan;
use Auth;
use Carbon\Carbon;
use Exception;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Mail;
use PDF;
use Razorpay\Api\Api;
use Session;
use Throwable;

class RazorpayController extends Controller {
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function setup(Request $request) {
        if (env('DEMO_MODE') === 'YES') {
            Alert::warning('warning', 'This is demo purpose only');

            return back();
        }

        overWriteEnvFile('RAZORPAY_KEY', $request->razorpay_key);
        overWriteEnvFile('RAZORPAY_SECRET', $request->razorpay_secret);

        notify()->success(translate('Razorpay setup done'));

        return back();
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index() {
        return view('payment_setup.razorpay');
    }

    /**
     * gateway
     */
    public function gateway(Request $request) {
        if (env('DEMO_MODE') === 'YES') {
            Alert::warning('warning', 'This is demo purpose only');

            return back();
        }

        $check_user = User::where('email', $request->email)->first();

        // if ($check_user == null) {
        $subscriptoin_plan_id = $request->subscriptoin_plan_id;
        $plan_name = $request->plan_name;
        $amount = RazorPayPrice($request->amount);
        $payment_type = $request->payment_type;

        $name = $request->name;
        $email = $request->email;

        /**
         * RAZORPAY DATA
         */
        $receiptId = Str::random(20);
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));


        $order = $api->order->create([
                        'receipt' => $receiptId,
                        'amount' => 500 * 100,
                        'currency' => 'INR',
                    ]);

        $response = [
            'orderId' => $order['id'],
            'razorpayId' => env('RAZORPAY_KEY'),
            'amount' => $amount * 100,
            'name' => $name,
            'currency' => 'INR',
            'email' => $email,
            'contactNumber' => '1111111111',
            'address' => 'unknown',
            'description' => orgName().' - '.$plan_name.' plan',
        ];

        /**
         * RAZORPAY DATA::END
         */

        return view('razorpay.index', compact(
            'subscriptoin_plan_id',
            'plan_name',
            'amount',
            'payment_type',
            'name',
            'email',
            'response'
        ));
        // } else {
        //     Alert::error(translate('Whoops'), translate('User Already Exist'));

        //     return back();
        // }
    }

    /**
     * MakePayment
     */
    public function MakePayment(Request $request) {
        //Let's validate
        $paymentStatus = $this->ValidateOrderID(
            $request->all()['rzp_signature'],
            $request->all()['rzp_paymentid'],
            $request->all()['rzp_orderid']
        );
        if ($paymentStatus == true) {

            if (Auth::check()) {

                /**
                 * PURCHASE CHECK
                 */
                $plan = new PlanPurchased();
                $plan->user_id = Auth::user()->id;
                $plan->plan_id = $request->subscriptoin_plan_id;
                $plan->plan_name = $request->plan_name;
                $plan->invoice = invoiceNumber();
                $plan->price = $request->amount;
                $plan->gateway = 'razorpay';
                $plan->status = true;
                $plan->save();

                $plan_details = SubscriptionPlan::where('id', $plan->plan_id)->first();

                $new_limit = new UserSentLimitPlan();
                $new_limit->owner_id = Auth::user()->id;
                $new_limit->plan_name = $plan_details->name;
                $new_limit->limit = $plan_details->emails;
                $new_limit->from = Carbon::now();
                $new_limit->to = Carbon::now()->addMonths($plan_details->duration);
                $new_limit->status = true;
                $new_limit->save();

                /**
                 * EMAIL SMS LIMIT RATE
                 */
                $check_email_sms_rate = EmailSMSLimitRate::HasAgent()->first();

                if ($check_email_sms_rate == null) {
                    $email_sms_rate = new EmailSMSLimitRate();
                    $email_sms_rate->owner_id = $user->id;
                    $email_sms_rate->email = $plan_details->emails;
                    $email_sms_rate->sms = $plan_details->sms;
                    $email_sms_rate->from = Carbon::now();
                    $email_sms_rate->to = Carbon::now()->addMonths($plan_details->duration);
                    $email_sms_rate->status = true;
                    $email_sms_rate->save();
                } else {
                    $update_email_sms_rate = EmailSMSLimitRate::HasAgent()->first();
                    $update_email_sms_rate->owner_id = Auth::user()->id;
                    $update_email_sms_rate->email = $check_email_sms_rate->email += $plan_details->emails;
                    $update_email_sms_rate->sms = $check_email_sms_rate->sms += $plan_details->sms;
                    $update_email_sms_rate->from = Carbon::now();
                    $update_email_sms_rate->to = Carbon::parse($check_email_sms_rate->to)->addMonths($plan_details->duration);
                    $update_email_sms_rate->status = true;
                    $update_email_sms_rate->save();
                }

                $details = new Demo();
                $details->user_id = $plan->user_id;
                $details->plan_id = $plan->plan_id;
                $details->invoice = $plan->invoice;
                $details->date = $plan->created_at->format('d/m/y');
                $details->price = $plan->price;
                $details->gateway = 'razorpay';
                $details->status = $plan->status;
                $details->purchase_id = $plan->id;

                /**
                 * SENDING MAIL
                 * STORING PDF
                 * PDF ATTACHMENT
                 */
                try {
                    $pdf = PDF::loadView('success.attachment_invoice', compact(
                        'details',
                    ))->save(invoice_path($details->invoice));

                    Mail::to(getUser($details->user_id)->email)->send(new InvoiceMail($details));
                } catch (Throwable $th) {
                    //throw $th;
                }

                return view('success.order_success');
            } else {

                /**
                 * REGISTERING USER
                 */
                $user = new User();
                $user->name = $request->name;
                $user->email = $request->email;
                $user->password = Hash::make($request->password);
                $user->slug = Str::slug($request->name).rand(100, 1000);
                $user->visitor = $_SERVER['REMOTE_ADDR'];
                $user->save();

                $plan = new PlanPurchased();
                $plan->user_id = $user->id;
                $plan->plan_id = $request->subscriptoin_plan_id;
                $plan->plan_name = $request->plan_name;
                $plan->price = $request->amount;
                $plan->invoice = invoiceNumber();
                $plan->gateway = $request->payment_type;
                $plan->status = true;
                $plan->save();

                $plan_details = SubscriptionPlan::where('id', $plan->plan_id)->first();

                $new_limit = new UserSentLimitPlan();
                $new_limit->owner_id = $user->id;
                $new_limit->plan_name = $plan_details->name;
                $new_limit->limit = $plan_details->emails;
                $new_limit->from = Carbon::now();
                $new_limit->to = Carbon::now()->addMonths($plan_details->duration);
                $new_limit->status = true;
                $new_limit->save();

                $email_sms_rate = new EmailSMSLimitRate();
                $email_sms_rate->owner_id = $user->id;
                $email_sms_rate->email = $plan_details->emails;
                $email_sms_rate->sms = $plan_details->sms;
                $email_sms_rate->from = Carbon::now();
                $email_sms_rate->to = Carbon::now()->addMonths($plan_details->duration);
                $email_sms_rate->status = true;
                $email_sms_rate->save();
            }

            $details = new Demo();
            $details->user_id = $plan->user_id;
            $details->plan_id = $plan->plan_id;
            $details->invoice = $plan->invoice;
            $details->date = $plan->created_at->format('d/m/y');
            $details->price = $plan->price;
            $details->gateway = 'razorpay';
            $details->status = $plan->status;
            $details->purchase_id = $plan->id;

            /**
             * SENDING MAIL
             * STORING PDF
             * PDF ATTACHMENT
             */
            try {
                $pdf = PDF::loadView('success.attachment_invoice', compact(
                    'details',
                ))->save(invoice_path($details->invoice));

                Mail::to(getUser($details->user_id)->email)->send(new InvoiceMail($details));
            } catch (Throwable $th) {
                //throw $th;
            }
        } else {
            Session::put('error', 'Payment failed !!');

            return view('errors.payment_failed');
        }

        return view('success.order_success');
    }

    public function razorpayEnableDisable() {
        if (env('DEMO_MODE') === 'YES') {
            Alert::warning('warning', 'This is demo purpose only');

            return back();
        }
        if (env('RAZORPAY') == 'YES') {
            overWriteEnvFile('RAZORPAY', 'NO');
            notify()->success(translate('Razor Pay Disable done'));
        } else {
            overWriteEnvFile('RAZORPAY', 'YES');
            notify()->success(translate('Razor Pay Enable done'));
        }

        return back();
    }

    /**
     * ValidateOrderID
     */
    private function ValidateOrderID($signature, $paymentId, $orderId) {
        try {
            $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
            $attributes = ['razorpay_signature' => $signature,  'razorpay_payment_id' => $paymentId,  'razorpay_order_id' => $orderId];
            $order = $api->utility->verifyPaymentSignature($attributes);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    //ENDS
}
