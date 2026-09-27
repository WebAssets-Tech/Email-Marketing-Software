<?php

namespace App\Http\Controllers;

use App\Models\ApiKey;
use App\Models\Demo;
use App\Models\EmailContact;
use App\Models\ThirdParty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;

class ActiveCmsController extends Controller {
    // index
    public function index() {
        $activecms = ThirdParty::where('application_name', 'activecms')->where('user_id', Auth::user()->id)->first();

        if (! $activecms) {
            session()->forget('activecms');
        }

        $activecms = ThirdParty::where('application_name', 'activecms')->where('user_id', Auth::user()->id)->first();

        return view('activecms.index', compact('activecms'));
    }

    // index
    public function store(Request $request) {
        $activecms = ThirdParty::where('application_name', 'activecms')->where('user_id', Auth::user()->id)->first();

        if ($activecms) {
            $update_activecms = ThirdParty::where('application_name', 'activecms')->where('user_id', Auth::user()->id)->first();
            $update_activecms->user_id = Auth::id();
            $update_activecms->application_name = 'activecms';

            $application_url_str = $request->application_url;
            $application_url_str1 = trim($application_url_str, 'http://');
            $application_url_str2 = trim($application_url_str1, 'https://');
            $application_url = trim($application_url_str2, 'www.');

            $update_activecms->application_url = $application_url;
            $update_activecms->user_email = $request->user_email;
            $update_activecms->save();
        } else {
            $new_activecms = new ThirdParty;
            $new_activecms->user_id = Auth::id();
            $new_activecms->application_name = 'activecms';

            $application_url_str = $request->application_url;
            $application_url_str1 = trim($application_url_str, 'http://');
            $application_url_str2 = trim($application_url_str1, 'https://');
            $application_url = trim($application_url_str2, 'www.');

            $new_activecms->application_url = $application_url;
            $new_activecms->user_email = $request->user_email;
            $new_activecms->save();
        }

        if ($activecms) {
            $update_activecms_apikey = ApiKey::where('app_key', 'activecms')->where('owner_id', Auth::user()->id)->first();

            $update_activecms_apikey->owner_id = Auth::id();
            $update_activecms_apikey->app_key = 'activecms';
            $update_activecms_apikey->save();
        } else {
            $activecms_apikey = new ApiKey;
            $activecms_apikey->owner_id = Auth::id();
            $activecms_apikey->app_key = 'activecms';
            $activecms_apikey->save();
        }

        Alert::success('success', 'Successfully saved');

        return back();
    }

    /**
     * generate_token
     */
    public function generate_token(Request $request) {
        $activecms = ThirdParty::where('application_name', 'activecms')->where('user_id', Auth::user()->id)->first();
        $activecms_remote = json_decode(Http::get($activecms->application_url.'/'.'maildoll/token'));

        if ($activecms_remote->admin_email == $activecms->user_email && $activecms_remote->application_url == $activecms->application_url && $activecms_remote->token == '7469a286259799e5b37e5db9296f00b3') {

            $activecms->update([
                'user_token' => Hash::make($activecms->user_email),
            ]);
            $activecms_apikey = ApiKey::where('app_key', 'activecms')->where('owner_id', Auth::user()->id)->first();
            $randomkey = rand(1000, 50000).$activecms_apikey->owner_id;
            $activecms_apikey->update([
                'app_secret_key' => $randomkey,
                'token' => $activecms->user_token,
            ]);
            Alert::success('success', 'Token Generated Successfully.');
        } else {
            Alert::error('error', 'Please check admin email url.');

            return back();
        }

        return back();
    }

    /**
     * fetch_data
     */
    public function fetch_data() {
        /* Clearing the session. */
        session()->forget('activecms_session');

        $activecms_apikey_token = ApiKey::where('app_key', 'activecms')->where('owner_id', Auth::user()->id)->first()->token;
        if ($activecms_apikey_token) {

            $activecms = ThirdParty::where('application_name', 'activecms')->where('user_id', Auth::user()->id)->first();
            $resp = Http::get('https://'.$activecms->application_url.'/maildoll');
            $alluser = json_decode($resp->body());

            $collect = collect();

            /* Getting the data from the response and storing it in variables. */
            foreach ($alluser as $contact) {
                $name = $contact->name;
                $email = $contact->email;
                $phonenumber = $contact->phone;

                $demo = new Demo;
                $demo->name = $name;
                $demo->email = $email;
                $demo->phonenumber = $phonenumber;

                $collect->push($demo);
            }

            /* Storing the data in a session. */
            $session = session()->put('activecms_session', $collect);
        }
        /* This is a way of showing a success message to the user. */
        Alert::success('success', 'Contacts Fetched Successfully');

        return back();
    }

    /**
     * fetch_data
     */
    public function fetch_data_store() {
        $counter = 0;

        /* Storing the data in the database. */
        foreach (session('activecms_session') as $contact) {
            // check if the email exists
            $check_email_or_phone_exists = EmailContact::HasAgent()
                ->where('email', $contact->email)
                ->where('phone', substr($contact->phonenumber, 4))
                ->first();
            /* Checking if the email or phone number exists in the database. If it does not exist, it will store it in the database. */
            if ($check_email_or_phone_exists == null) {
                $email = new EmailContact();
                $email->owner_id = userId();
                $email->name = $contact->name;
                $email->email = $contact->email;
                $email->country_code = substr($contact->phonenumber, 1, 3);
                $email->phone = substr($contact->phonenumber, 4);
                $email->save();

                $counter++;
            }
        }

        /* This is a ternary operator. It is a way of writing an if statement in one line. */
        $message = $counter.' New '.Str::plural('contacts', $counter).' stored';

        /* This is a way of showing a success message to the user. */
        Alert::success('success', $message);

        return back();

    }
}
