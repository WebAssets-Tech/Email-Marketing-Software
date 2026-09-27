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
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Mail;
use Mollie\Laravel\Facades\Mollie;
use PDF;
use Session;
use Throwable;

class MollieController extends Controller {
    /**
     * __construct
     */
    public function __construct() {
    }

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

        overWriteEnvFile('MOLLIE_KEY', $request->MOLLIE_KEY);
        overWriteEnvFile('MOLLIE_PARTNER_ID', $request->MOLLIE_PARTNER_ID);
        overWriteEnvFile('MOLLIE_PROFILE_ID', $request->MOLLIE_PROFILE_ID);

        notify()->success(translate('Mollie setup done'));

        return back();
    }

    /**
     * INDEX
     */
    public function index() {
        return view('payment_setup.mollie');
    }

    // preparePayment
    public function preparePayment(Request $request) {

        if (env('DEMO_MODE') === 'YES') {
            Alert::warning('warning', 'This is demo purpose only');

            return back();
        }

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
            $plan->gateway = $request->payment_type;
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
                $email_sms_rate->owner_id = Auth::user()->id;
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
            $details->gateway = $request->payment_type;
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
            if (env('DEMO_MODE') === 'YES') {
                Alert::warning('warning', 'This is demo purpose only');

                return back();
            }

            /**
             * REGISTERING USER
             */
            if (checkUser($request->email) == null) {
                $user = new User();
                $user->name = $request->name;
                $user->email = $request->email;
                $user->password = Hash::make($request->password);
                $user->slug = Str::slug($request->name).rand(100, 1000);
                $user->visitor = $_SERVER['REMOTE_ADDR'];
                $user->save();
            } else {
                Alert::error(@translate('Whoops'), @translate('User Already Exist'));

                return back();
            }

            if (env('DEMO_MODE') === 'YES') {
                Alert::warning('warning', 'This is demo purpose only');

                return back();
            }

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
        $details->gateway = $request->payment_type;
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

        $payment = Mollie::api()->payments()->create([
            'amount' => [
                'currency' => 'EUR', // Type of currency you want to send
                'value' => '10.00', // You must send the correct number of decimals, thus we enforce the use of strings
            ],
            'description' => 'Payment for '.$plan->plan_name.'plan',
            'redirectUrl' => route('frontend.success'), // after the payment completion where you to redirect
            'webhookUrl' => 'http://www.example.org', // after the payment completion where you to redirect
            // 'webhookUrl' => route('webhooks.mollie'), // after the payment completion where you to redirect
            'metadata' => [
                'order_id' => '12345', // We can add more data here
            ],
        ]);

        // redirect customer to Mollie checkout page
        return redirect($payment->getCheckoutUrl(), 303);
    }

    /**
     * After the customer has completed the transaction,
     * you can fetch, check and process the payment.
     * This logic typically goes into the controller handling the inbound webhook request.
     * See the webhook docs in /docs and on mollie.com for more information.
     */
    public function handleWebhookNotification(Request $request) {
        $paymentId = $request->input('id');
        $payment = Mollie::api()->payments->get($paymentId);

        if ($payment->isPaid()) {
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
                $plan->gateway = $request->payment_type;
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
                $details->gateway = $request->payment_type;
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
            $details->gateway = $request->payment_type;
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

                return view('success.order_success');
            } catch (Throwable $th) {
                Session::put('error', 'Some error occur, sorry for inconvenient');

                return view('errors.payment_failed');
            }
        }
    }

    public function mollieEnableDisable() {
        if (env('DEMO_MODE') === 'YES') {
            Alert::warning('warning', 'This is demo purpose only');

            return back();
        }
        if (env('MOLLIE') == 'YES') {
            overWriteEnvFile('MOLLIE', 'NO');
            notify()->success(translate('Mollie Disable done'));
        } else {
            overWriteEnvFile('MOLLIE', 'YES');
            notify()->success(translate('Mollie Enable done'));
        }

        return back();
    }

    //ENDS
}
