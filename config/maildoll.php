<?php

return [

    'patch' => true, // Patch true or false. If true, maildoll will fetch all the lastest updated files.

    'last_patch_at' => Carbon\Carbon::today()->format('d-m-Y H:i:s'), // Last patch date time. Date - Month - Year Hour:Minute:Second. 07-07-2022 08:00:00

    'khalti' => true, // Khalti true or false. If true, then you need to add your Khalti API key and secret key.

    'flutterwave' => true, // Flutterwave true or false. If true, then you need to add your Flutterwave API key and secret key.

    'instamojo' => true, // instamojo true or false. If true, then you need to add your instamojo API key and secret key.

    'paystack' => true, // paystack true or false. If true, then you need to add your paystack API key and secret key.

    'razorpay' => true, // razorpay true or false. If true, then you need to add your razorpay API key and secret key.

    'paytm' => true, // razorpay true or false. If true, then you need to add your paytm API key and secret key.

    'mollie' => true, // razorpay true or false. If true, then you need to add your mollie API key
    'paytm' => true, // razorpay true or false. If true, then you need to add your mollie API key

    'site_name' => env('APP_NAME', 'Maildoll'), // Site name.

];
