<?php

namespace App\Http\Controllers\Payment;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use App\Contracts\PaymentProcessor;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Requests\Payment\PaymentRequest;

class PaymentController extends Controller
{
    public function index(SubscriptionPlan $plan)
    {
        $isFree = $plan->price < 1;

        if ($isFree && auth()->check()) {
            assignFreePlanToUser(auth()->user(), $plan);
            Alert::success(translate('Success'), translate('Free plan activated successfully!'));
            return redirect()->route('dashboard');
        }

        return view('payment.index', compact('plan', 'isFree'));
    }

    public function pay(PaymentRequest $request, PaymentProcessor $paymentProcessor)
    {
        
        $user = auth()->user();
        if ($user != null && $request->email != $user?->email) {
            Alert::error('Sorry!', 'Provided email doesn\'t matched with the account email.');
            return back();
        }
        if (!auth()->check()) {
            $user = User::where('email', $request->email)->first();
            $passMatched = Hash::check($request->password, $user?->password);
            if ($user != null && !$passMatched) {
                Alert::error('Sorry!', 'Incorrect password provided.');
                return back();
            }
        }
        $subscription = SubscriptionPlan::where('id', $request->plan_id)->first();


        try {
            if ($paymentProcessor->shouldRedirect) {
                return $paymentProcessor->redirect($request, $subscription);
            } else {
                $success = $paymentProcessor->handle($request, $subscription);
                $view = $success ? $paymentProcessor->successView : $paymentProcessor->failedView;
                return view($view);
            }
        } catch (\Exception $e) {
            Alert::error('Error!', $e->getMessage());
            return back();
        }
    }

    public function callback(PaymentRequest $request, PaymentProcessor $paymentProcessor)
    {
        // file_put_contents(base_path('callback.txt'), json_encode($request->all()));
        $subscription = SubscriptionPlan::where('id', $request->plan_id)->first();
        $success = $paymentProcessor->handle($request, $subscription);
        if ($success) {
            return redirect()->route('payment.success');
        } else {
            return redirect()->route('payment.failed');
        }
    }
    public function ipn(Request $request, PaymentProcessor $paymentProcessor)
    {
        // file_put_contents(base_path('ipn.txt'), json_encode($request->all()));
        $success = $paymentProcessor->ipn($request);
    }

    public function success()
    {
        return view('payment.success');
    }
    public function failed()
    {
        return view('payment.failed');
    }
}
