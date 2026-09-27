<?php

namespace App\Http\Controllers;

use Str;
use Auth;
use App\Models\Demo;
use App\Models\EmailGroup;
use App\Models\ThirdParty;
use App\Models\EmailContact;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class PerfexController extends Controller
{
    // index
    public function index()
    {
        $perfex = ThirdParty::where('application_name', 'perfex')->where('user_id', Auth::user()->id)->first();

        if (!$perfex) {
            session()->forget('perfex');
        }

        $perfex = ThirdParty::where('application_name', 'perfex')->where('user_id', Auth::user()->id)->first();
        return view('perfex.index', compact('perfex'));
    }
    // index
    public function store(Request $request)
    {

        $perfex = ThirdParty::where('application_name', 'perfex')->where('user_id', Auth::user()->id)->first();

        if ($perfex) {
            $update_perfex = ThirdParty::where('application_name', 'perfex')->where('user_id', Auth::user()->id)->first();
            $update_perfex->user_id = Auth::id();
            $update_perfex->application_name = 'perfex';
            $update_perfex->application_url = $request->application_url;
            $update_perfex->user_email = $request->user_email;
            $update_perfex->save();
        }else {
            $new_perfex = new ThirdParty;
            $new_perfex->user_id = Auth::id();
            $new_perfex->application_name = 'perfex';
            $new_perfex->application_url = $request->application_url;
            $new_perfex->user_email = $request->user_email;
            $new_perfex->save();
        }
        
        Alert::success('success', 'Successfully saved');
        return back();
    }

    /**
     * generate_token
     */
    public function generate_token(Request $request)
    {

        /* Getting the first record from the database where the user_id is equal to the logged in
        user's id. */
        $perfex = ThirdParty::where('application_name', 'perfex')->where('user_id', Auth::user()->id)->first();

        /* Getting the email and url from the database. */
        $email = $perfex->user_email;
        $url = $perfex->application_url;

        /* Concatenating the url and email to form a complete url. */
        $api_url = $perfex->application_url . '/maildoll/generate/' . $email;

        /* This is a curl request to the perfex application. */
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

        /* This is a way of getting the token from the response. */
        $response = json_decode($response);
        /* This is a way of getting the token from the response. */
        if ($response->success ?? false) {
            $perfex->user_token =  $response->data?->token;
            $perfex->save();
        }else {
            Alert::error('error', 'Something went wrong!');
            return back();
        }

        /* This is a way of showing a success message to the user. */
        Alert::success('success', 'Token Generated Successfully.');
        return back();
    }

    /**
     * fetch_data
     */
    public function fetch_data()
    {
        /* Clearing the session. */
        session()->forget('perfex');

        /* Getting the first record from the database where the user_id is equal to the logged in
        user's id. */
        $perfex = ThirdParty::where('application_name', 'perfex')->where('user_id', Auth::user()->id)->first();

        /* Getting the email and url from the database. */
        $email = $perfex->user_email;
        $url = $perfex->application_url;
        $token = $perfex->user_token;

        /* Concatenating the url and email to form a complete url. */
        $api_url = $perfex->application_url . '/maildoll/leads/'. $email . '/' . $token;

        /* This is a curl request to the perfex application. */
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

		$response = json_decode($response);

        if($response->success ?? false){
           $session = session()->put('perfex', collect($response->data));
          Alert::success('success', 'Contacts Fetched');
          return back();
        }else{
          Alert::success('error', 'Something went wrong!');
          return back();
        }

      
    }

    /**
     * Store
     */
    public function store_to_database()
    {

        $counter = 0;

        /* Storing the data in the database. */
        foreach (session('perfex') as $contact) {

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
        $message = $counter . ' New ' . Str::plural('contacts', $counter) . ' stored';

        /* This is a way of showing a success message to the user. */
        Alert::success('success', $message);
        return back();
         
    }

    //ENDS
}
