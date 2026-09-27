<?php

namespace App\Http\Controllers;

use Alert;
use Illuminate\Http\Request;

class PaymentSetupController extends Controller
{
    // Paypal

    public function paypal()
    {
        return view('payment_setup.paypal');
    }

    public function paypalCreate(Request $request)
    {

        if (env('DEMO_MODE') === 'YES') {
            Alert::warning('warning', 'This is demo purpose only');

            return back();
        }

        overWriteEnvFile('PAYPAL_CLIENT_ID', $request->paypal_client_id);
        overWriteEnvFile('PAYPAL_SECRET', $request->paypal_secret);
        overWriteEnvFile('PAYPAL_MODE', $request->paypal_mode);

        telling(route('payment.setup.paypal'), translate('PayPal Account Setup Completed'));

        notify()->success(translate('Paypal setup done'));

        return back();
    }

    public function paypalDisable(Request $request)
    {
        // return $request->all();
        if (env('DEMO_MODE') === 'YES') {
            Alert::warning('warning', 'This is demo purpose only');

            return back();
        }
        if (env('PAYPAL_PAYMENT') == 'YES') {
            overWriteEnvFile('PAYPAL_PAYMENT', 'NO');

            notify()->success(translate('Paypal Disable done'));
        } else {
            overWriteEnvFile('PAYPAL_PAYMENT', 'YES');

            notify()->success(translate('Paypal Enable done'));
        }

        return back();
    }

    // Stripe

    public function stripe()
    {
        return view('payment_setup.stripe');
    }

    public function stripeCreate(Request $request)
    {
        if (env('DEMO_MODE') === 'YES') {
            Alert::warning('warning', 'This is demo purpose only');

            return back();
        }

        overWriteEnvFile('STRIPE_KEY', $request->stripe_client_id);
        overWriteEnvFile('STRIPE_SECRET', $request->stripe_secret);

        telling(route('payment.setup.stripe'), translate('Stripe Account Setup Completed'));

        notify()->success(translate('Stripe setup done'));

        return back();
    }

    public function stripeEnableDisable(Request $request)
    {
        if (env('DEMO_MODE') === 'YES') {
            Alert::warning('warning', 'This is demo purpose only');

            return back();
        }
        if (env('STRIPE_PAYMENT') == 'YES') {
            overWriteEnvFile('STRIPE_PAYMENT', 'NO');
            notify()->success(translate('Stripe Disable done'));
        } else {
            overWriteEnvFile('STRIPE_PAYMENT', 'YES');
            notify()->success(translate('Stripe Enable done'));
        }

        return back();
    }

    // khalti

    public function khalti()
    {
        return view('payment_setup.khalti');
    }

    public function khaltiCreate(Request $request)
    {
        if (env('DEMO_MODE') === 'YES') {
            Alert::warning('warning', 'This is demo purpose only');

            return back();
        }

        overWriteEnvFile('KHALTI_KEY', $request->khalti_client_id);
        overWriteEnvFile('KHALTI_SECRET', $request->khalti_secret);

        telling(route('payment.setup.khalti'), translate('khalti Account Setup Completed'));

        notify()->success(translate('khalti setup done'));

        return back();
    }

    public function khaltiEnableDisable(Request $request)
    {
        if (env('DEMO_MODE') === 'YES') {
            Alert::warning('warning', 'This is demo purpose only');

            return back();
        }
        if (env('KHALTI_PAYMENT') == 'YES') {
            overWriteEnvFile('KHALTI_PAYMENT', 'NO');
            notify()->success(translate('Stripe Disable done'));
        } else {
            overWriteEnvFile('KHALTI_PAYMENT', 'YES');
            notify()->success(translate('Stripe Enable done'));
        }

        return back();
    }

    //END
}
