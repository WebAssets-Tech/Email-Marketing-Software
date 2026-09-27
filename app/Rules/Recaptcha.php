<?php

namespace App\Rules;

use Exception;
use Illuminate\Contracts\Validation\Rule;

class Recaptcha implements Rule {
    public function __construct() {
        // Constructor
    }

    public function passes($attribute, $value) {
        $data = [
            'secret' => env('GOOGLE_RECAPTCHA_SECRET'), // Google reCaptcha secret key
            'response' => $value, // the key given in the form
        ];

        try { // try to get the response from Google reCaptcha
            $verify = curl_init(); // Initialize cURL
            curl_setopt($verify, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify'); // Set cURL URL to reCaptcha
            curl_setopt($verify, CURLOPT_POST, true); // Tell cURL you're sending a POST request
            curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data)); // Add the data to your post
            curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification
            curl_setopt($verify, CURLOPT_RETURNTRANSFER, true); // Return the response from cURL
            $response = curl_exec($verify); // Execute cURL call

            return json_decode($response)->success; // Return the reCaptcha success
        } catch (Exception $e) { // Catch any exceptions
            return false; // Return false
        } // end of try catch

    }

    public function message() {
        return 'ReCaptcha verification failed.'; // Return error message
    }
}
