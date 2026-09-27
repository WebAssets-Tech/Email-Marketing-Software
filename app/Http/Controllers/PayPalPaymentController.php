<?php

namespace App\Http\Controllers;

use App\Mail\InvoiceMail;
use App\Models\Demo;
use App\Models\EmailSMSLimitRate;
use App\Models\PlanPurchased;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\UserSentLimitPlan;
use App\Services\Payment\FreePayment;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use RealRashid\SweetAlert\Facades\Alert;
use Symfony\Component\HttpFoundation\Session\Session;
use Throwable;

class PayPalPaymentController extends Controller {
    private $_api_context;

    public function __construct() {
    }

    /**
     * FREE
     */
    public function freePayment(Request $request) {
        if (env('DEMO_MODE') === 'YES') {
            Alert::warning('warning', 'This is demo purpose only');

            return back();
        }
        try {
            $payment = new FreePayment('free');
            $payment->process($request);

            return view($payment->successView);
        } catch (Throwable $th) {
            return view($payment->failedView);
        }
    }

    //PAYPAL

    public function postPaymentWithpaypal(Request $request) {

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
            $plan->status = false;
            $plan->save();

            $plan_details = SubscriptionPlan::where('id', $plan->plan_id)->first();

            $new_limit = new UserSentLimitPlan();
            $new_limit->owner_id = Auth::user()->id;
            $new_limit->plan_name = $plan_details->name;
            $new_limit->limit = $plan_details->emails;
            $new_limit->from = Carbon::now();
            $new_limit->to = Carbon::now()->addMonths($plan_details->duration);
            $new_limit->status = false;
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
                $email_sms_rate->status = false;
                $email_sms_rate->save();
            } else {
                $update_email_sms_rate = EmailSMSLimitRate::HasAgent()->first();
                $update_email_sms_rate->owner_id = Auth::user()->id;
                $update_email_sms_rate->email = $check_email_sms_rate->email += $plan_details->emails;
                $update_email_sms_rate->sms = $check_email_sms_rate->sms += $plan_details->sms;
                $update_email_sms_rate->from = Carbon::now();
                $update_email_sms_rate->to = Carbon::parse($check_email_sms_rate->to)->addMonths($plan_details->duration);
                $update_email_sms_rate->status = false;
                $update_email_sms_rate->save();
            }
        } else {

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

                $request->session()->forget('new_user_id');
                $request->session()->put('new_user_id', $user->id);
            } else {
                Alert::error(@translate('Whoops'), @translate('User Already Exist'));

                return back();
            }

            $plan = new PlanPurchased();
            $plan->user_id = $user->id;
            $plan->plan_id = $request->subscriptoin_plan_id;
            $plan->plan_name = $request->plan_name;
            $plan->price = $request->amount;
            $plan->invoice = invoiceNumber();
            $plan->gateway = $request->payment_type;
            $plan->status = false;
            $plan->save();

            $plan_details = SubscriptionPlan::where('id', $plan->plan_id)->first();

            $new_limit = new UserSentLimitPlan();
            $new_limit->owner_id = $user->id;
            $new_limit->plan_name = $plan_details->name;
            $new_limit->limit = $plan_details->emails;
            $new_limit->from = Carbon::now();
            $new_limit->to = Carbon::now()->addMonths($plan_details->duration);
            $new_limit->status = false;
            $new_limit->save();

            $email_sms_rate = new EmailSMSLimitRate();
            $email_sms_rate->owner_id = $user->id;
            $email_sms_rate->email = $plan_details->emails;
            $email_sms_rate->sms = $plan_details->sms;
            $email_sms_rate->from = Carbon::now();
            $email_sms_rate->to = Carbon::now()->addMonths($plan_details->duration);
            $email_sms_rate->status = false;
            $email_sms_rate->save();
        }

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $item_1 = new Item();

        $item_1->setName('Product 1')
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice($request->get('amount'));

        $item_list = new ItemList();
        $item_list->setItems([$item_1]);

        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal($request->get('amount'));

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription('Enter Your transaction description');

        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(URL::route('getPaymentStatus'))
            ->setCancelUrl(URL::route('getPaymentStatus'));

        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions([$transaction]);

        try {
            $payment->create($this->_api_context);
        } catch (\PayPal\Exception\PPConnectionException $ex) {
            if (Config::get('app.debug')) {
                Session::put('error', 'Connection timeout');
            } else {
                Session::put('error', 'Some error occur, sorry for inconvenient');

                return view('errors.payment_failed');
            }
        }

        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }

        Session::put('paypal_payment_id', $payment->getId());

        if (isset($redirect_url)) {
            return Redirect::away($redirect_url);
        }

        Session::put('error', 'Unknown error occurred');

        return view('errors.payment_failed');
    }

    public function getPaymentStatus(Request $request) {
        // $payment_id = Session::get('paypal_payment_id');

        // \Session::forget('paypal_payment_id');
        // if (empty($request->input('PayerID')) || empty($request->input('token'))) {
        //     \Session::put('error', 'Payment failed');

        //     return view('errors.payment_failed');
        // }

        // $payment = Payment::get($payment_id, $this->_api_context);
        // $execution = new PaymentExecution();
        // $execution->setPayerId($request->input('PayerID'));
        // $result = $payment->execute($execution, $this->_api_context);

        // if ($result->getState() == 'approved') {
        \Session::put('success', 'Payment success !!');

        $userId = auth()->user() ? auth()->id() : $request->session()->get('new_user_id');
        /**
         * PURCHASE CHECK
         */
        $success_plan = PlanPurchased::where('user_id', $userId)->latest()->first();
        $success_plan->status = true;
        $success_plan->save();

        $plan_details = SubscriptionPlan::where('id', $success_plan->plan_id)->first();

        $success_limit = UserSentLimitPlan::HasAgent()->first();
        $success_limit->status = true;
        $success_limit->save();

        /**
         * EMAIL SMS LIMIT RATE
         */
        $update_email_sms_rate = EmailSMSLimitRate::HasAgent()->first();
        $update_email_sms_rate->status = true;
        $update_email_sms_rate->save();

        /**
         * SENDING MAIL
         * STORING PDF
         * PDF ATTACHMENT
         */
        $details = new Demo();
        $details->user_id = $success_plan->user_id;
        $details->plan_id = $success_plan->plan_id;
        $details->invoice = $success_plan->invoice;
        $details->date = $success_plan->created_at->format('d/m/y');
        $details->price = $success_plan->price;
        $details->gateway = 'paypal';
        $details->status = $success_plan->status;
        $details->purchase_id = $success_plan->id;

        /**
         * SENDING MAIL
         * STORING PDF
         * PDF ATTACHMENT
         */
        try {
            $pdf = PDF::loadView(
                'success.attachment_invoice',
                compact('details')
            )->save(invoice_path($details->invoice));

            Mail::to(getUser($details->user_id)->email)->send(new InvoiceMail($details));
        } catch (Throwable $th) {
            //throw $th;
        }

        return view('success.order_success');

        \Session::put('error', 'Payment failed !!');

        return view('errors.payment_failed');
    }

    //END
}
