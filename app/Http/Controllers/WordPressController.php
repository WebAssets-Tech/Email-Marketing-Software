<?php

namespace App\Http\Controllers;

use App\Models\ApiKey;
use App\Models\Demo;
use App\Models\ThirdParty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class WordPressController extends Controller {
    public function index() {
        $wordpress = ThirdParty::where('application_name', 'wordpress')->where('user_id', Auth::user()->id)->first();

        if (! $wordpress) {
            session()->forget('wordpress');
        }

        $wordpress = ThirdParty::where('application_name', 'wordpress')->where('user_id', Auth::user()->id)->first();

        return view('wordpress.index', compact('wordpress'));
    }

    public function store(Request $request) {

        $wordpress = ThirdParty::where('application_name', 'wordpress')->where('user_id', Auth::user()->id)->first();

        if ($wordpress) {
            $update_wordpress = ThirdParty::where('application_name', 'wordpress')->where('user_id', Auth::user()->id)->first();
            $update_wordpress->user_id = Auth::id();
            $update_wordpress->application_name = 'wordpress';
            $update_wordpress->application_url = $request->application_url;
            $update_wordpress->user_email = $request->user_email;
            $update_wordpress->save();
        } else {
            $new_wordpress = new ThirdParty;
            $new_wordpress->user_id = Auth::id();
            $new_wordpress->application_name = 'wordpress';
            $new_wordpress->application_url = $request->application_url;
            $new_wordpress->user_email = $request->user_email;
            $new_wordpress->save();
        }

        if ($wordpress) {
            $update_wordpress_apikey = ApiKey::where('app_key', 'wordpress')->where('owner_id', Auth::user()->id)->first();

            $update_wordpress_apikey->owner_id = Auth::id();
            $update_wordpress_apikey->app_key = 'wordpress';
            $update_wordpress_apikey->save();
        } else {
            $wordpress_apikey = new ApiKey;
            $wordpress_apikey->owner_id = Auth::id();
            $wordpress_apikey->app_key = 'wordpress';
            $wordpress_apikey->save();
        }

        Alert::success('success', 'Successfully saved');

        return back();
    }

    /**
     * generate_token
     */
    public function generate_token(Request $request) {
        // return "Hello world";

        /* Getting the first record from the database where the user_id is equal to the logged in
        user's id. */
        $wordpress = ThirdParty::where('application_name', 'wordpress')->where('user_id', Auth::user()->id)->first();
        $wordpress->update([
            'user_token' => Hash::make($wordpress->user_email),
        ]);

        $wordpress_apikey = ApiKey::where('app_key', 'wordpress')->where('owner_id', Auth::user()->id)->first();
        $randomkey = rand(1000, 50000).$wordpress_apikey->owner_id;
        $wordpress_apikey->update([
            'app_secret_key' => $randomkey,
            'token' => $wordpress->user_token,
        ]);

        /* This is a way of showing a success message to the user. */
        Alert::success('success', 'Token Generated Successfully.');

        return back();
    }

    /**
     * fetch_data
     */
    public function fetch_data() {
        /* Clearing the session. */
        session()->forget('wordpress');

        /* Getting the first record from the database where the user_id is equal to the logged in
        user's id. */
        $wordpress = ThirdParty::where('application_name', 'wordpress')->where('user_id', Auth::user()->id)->first();

        /* Getting the email and url from the database. */
        $email = $wordpress->user_email;
        $url = $wordpress->application_url;
        $token = $wordpress->user_token;

        /* Concatenating the url and email to form a complete url. */
        $api_url = $wordpress->application_url.'/maildoll?email='.$email.'&token='.$token;

        /* This is a curl request to the wordpress application. */
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $api_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [],
        ]);

        $response = curl_exec($curl);

        curl_close($curl);

        $collect = collect();

        /* Getting the data from the response and storing it in variables. */
        foreach (json_decode($response, true) as $contact) {
            $name = $contact['firstname'].' '.$contact['lastname'];
            $email = $contact['email'];
            $phonenumber = $contact['phonenumber'];

            $demo = new Demo;
            $demo->name = $name;
            $demo->email = $email;
            $demo->phonenumber = $phonenumber;

            $collect->push($demo);
        }

        /* Storing the data in a session. */
        $session = session()->put('wordpress', $collect);

        /* This is a way of showing a success message to the user. */
        Alert::success('success', 'Contacts Fetched');

        return back();
    }
}
