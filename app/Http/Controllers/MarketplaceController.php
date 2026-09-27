<?php

namespace App\Http\Controllers;

use Str;
use URL;
use File;
use Mail;
use Config;
use Stripe;
use Session;
use Redirect;
use PayPal\Api\Item;
use PayPal\Api\Payer;
use PayPal\Api\Amount;
use PayPal\Api\Payment;
use PayPal\Api\ItemList;
use PayPal\Api\Transaction;
use Illuminate\Http\Request;
use PayPal\Api\RedirectUrls;
use App\Models\MarketplaceCSV;
use App\Models\MarketplaceSell;
use PayPal\Api\PaymentExecution;
use App\Models\MarketplaceSetting;
use App\Services\Marketplace\PaymentService;
use RealRashid\SweetAlert\Facades\Alert;

class MarketplaceController extends Controller
{
    public function index()
    {
        return view('marketplace.index');
    }

    // csv_upload
    public function csv_upload(Request $request)
    {
        $country = Str::lower($request->country);

        if ($request->has('country')) {
            $check_country = MarketplaceCSV::where('country', $country)->first();
            if ($check_country == null) {
                $csv = new MarketplaceCSV;
                $csv->country = $request->country;
                $csv->type = 'email';
                $csv->save();
            } else {
                return response()->json('Country already exists', 201);
            }

            Session::put('country', $country);
        }

        if ($request->hasFile('filepond')) {
            $file_extension = strtolower($request->filepond->getClientOriginalExtension());

            if ($file_extension !== 'csv') {
                return response()->json('Please upload a valid CSV file.', 422);
            }

            $update_csv_path = MarketplaceCSV::where('country', Session::get('country'))->first();

            // Ensure directory exists
            $destinationPath = public_path('uploads/marketplace/csv/email');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            // Final file name: country.csv
            $finalFileName = Session::get('country') . '.csv';
            $request->filepond->move($destinationPath, $finalFileName);

            // Save just file name or full path in DB (your choice)
            $update_csv_path->csv_file_path = $finalFileName;
            $update_csv_path->save();

            Session::forget('country');
        }

        return response()->json('CSV file uploaded successfully', 200);
    }


    // csv_downlaoad
    public function csv_downlaoad($country_code)
    {
        $country = Str::lower($country_code);
        $csv_file_path = MarketplaceCSV::where('country', $country)->first();

        return response()->download(public_path('/uploads/marketplace/csv/email/' . $csv_file_path->csv_file_path));
    }

    // csv_destroy
    public function csv_destroy($country_code)
    {
        $country = Str::lower($country_code);
        $csv_file_path = MarketplaceCSV::where('country', $country)->first();
        // delete file
        unlink(public_path('/uploads/marketplace/csv/email/' . $csv_file_path->csv_file_path));

        // delete from marketplace_settings
        $marketplace_setting = MarketplaceSetting::where('csv_id', $csv_file_path->id)->delete();

        $csv_file_path->delete();
        Alert::success(translate('Success'), translate('CSV file deleted successfully'));

        return back();
    }

    // csv_update
    public function csv_update(Request $request, $country_code)
    {
        $country = Str::lower($country_code);
        $csv_file_path = MarketplaceCSV::where('country', $country)->first();

        // delete file
        unlink(public_path('/uploads/marketplace/csv/email/' . $csv_file_path->csv_file_path));

        $csv_file_path->csv_file_path = fileUpload($request->csv_update, 'marketplace/csv/email');
        $csv_file_path->save();

        // rename file name
        // rename(
        //     public_path($csv_file_path->csv_file_path),
        //     public_path('/uploads/marketplace/csv/email/' . $country_code . '.csv')
        // );

        $update_csv_name = MarketplaceCSV::where('country', $country_code)->first();
        $update_csv_name->csv_file_path = $country_code . '.csv';
        $update_csv_name->save();

        Alert::success(translate('Success'), translate('CSV file updated successfully'));

        return back();
    }

    // csv_settings
    public function csv_settings(Request $request)
    {
        $country = Str::lower($request->country);

        $csv = MarketplaceCSV::where('country', $country)->first();

        $csv_settings = new MarketplaceSetting;
        $csv_settings->csv_id = $csv->id;
        $csv_settings->min = $request->min;
        $csv_settings->max = $request->max;
        $csv_settings->each_price = $request->each_price;
        $csv_settings->type = 'email';
        $csv_settings->save();

        Alert::success(translate('Success'), translate('CSV settings updated successfully'));

        return back();
    }

    // csv_settings_update
    public function csv_settings_update(Request $request, $country_code)
    {
        $country = Str::lower($country_code);

        $csv = MarketplaceCSV::where('country', $country)->first();

        $csv_settings = MarketplaceSetting::where('csv_id', $csv->id)->first();

        $csv_settings->min = $request->min;
        $csv_settings->max = $request->max;
        $csv_settings->each_price = $request->each_price;
        $csv_settings->save();

        Alert::success(translate('Success'), translate('CSV settings updated successfully'));

        return back();
    }

    // marketplace_buyers
    public function marketplace_buyers()
    {
        $marketplace_buyers = MarketplaceSell::paginate(20);

        return view('marketplace.buyers', compact('marketplace_buyers'));
    }

    // FRONTEND
    public function frontend_index()
    {
        return view('marketplace.frontend.index');
    }

    public function get_country_csv(Request $request)
    {
        $country = Str::lower($request->country_code);
        $csv_data = MarketplaceCSV::where('country', $country)->with('marketplace_setting')->first();

        return response()->json($csv_data, 200);
    }

    // marketplace.payment
    public function marketplace_payment(Request $request)
    {
        $country = Str::lower($request->country_code);
        $quantity = $request->quantity;
        $total = $request->total;
        $csv_data = MarketplaceCSV::where('country', $country)->with('marketplace_setting')->first();

        return view('marketplace.frontend.payment', compact('csv_data', 'country', 'quantity', 'total'));
    }

    // PayPal payment initiation
    public function postPaymentWithPaypalMarketplace(Request $request)
    {
        try {
            $paymentService = new PaymentService();
            $order = $paymentService->createOrder($request->all(), 'paypal');
            return $paymentService->processPayPalPayment($order);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    // PayPal callback handler
    public function getPaymentStatus(Request $request)
    {
        try {
            $paymentService = new PaymentService();
            if ($paymentService->handlePayPalCallback($request)) {
                return view('success.order_success');
            }
        } catch (\Exception $e) {
            logger()->error('PayPal callback failed: ' . $e->getMessage());
        }
        return view('errors.payment_failed');
    }

    // Stripe payment processing
    public function handlePost(Request $request)
    {
        try {
            $paymentService = new PaymentService();
            $order = $paymentService->createOrder($request->all(), 'stripe');
            Session::put('sale_id', $order->id);

            if ($paymentService->processStripePayment($order, $request->stripeToken)) {
                return view('success.order_success');
            }
        } catch (\Exception $e) {
            logger()->error('Stripe payment failed: ' . $e->getMessage());
            return back()->withErrors($e->getMessage());
        }

        return view('errors.payment_failed');
    }
    /**
     * STRIPE
     */
    public function getPaymentWithStripe(Request $request)
    {
        $country = $request->country;
        $quantity = $request->email_amount;
        $total = $request->price;
        $type = $request->type;
        $gateway = $request->gateway;

        $name = $request->name;
        $email = $request->email;

        return view('marketplace.frontend.stripe', compact(
            'country',
            'quantity',
            'total',
            'type',
            'gateway',
            'name',
            'email'
        ));
    }

    /**
     * csv_viewer
     */
    public function csv_viewer()
    {
        return view('marketplace.frontend.csv_viewer');
    }

    /**
     * marketplace_send_file_to_buyer
     */
    public function marketplace_send_file_to_buyer($sale_id)
    {
        $sale = MarketplaceSell::where('id', $sale_id)->first();
        $data['email'] = $sale->email;
        $data['title'] = 'CSV Attachment';

        // check if file exists in the folder
        if (file_exists($sale->file_path)) {
            Mail::send('marketplace.csv_mail_file', ['data' => $data], function ($message) use ($data, $sale) {
                $message->to($data['email'], $data['email'])
                    ->subject($data['title']);

                $message->attach($sale->file_path);
            });
        } else {
            Alert::warning('warning', 'File not found. May be deleted.');

            return back();
        }

        Alert::success('success', 'Email sent successfully');

        return back();
    }

    //END
}
