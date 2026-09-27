<?php

namespace App\Http\Controllers;

use Alert;
use App\Models\Agent;
use App\Models\EmailSMSLimitRate;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Str;

class AgentController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $agents = Agent::where('assined_for_customer_id', Auth::user()->id)->get();

        return view('agents.index', compact('agents'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        /**
         * Validate the request
         */
        $this->validate($request, [ // validation rules
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ], [ // validation messages
            'name.required' => 'Name is required',
            'name.max' => 'Name is too long',
            'email.required' => 'Email is required',
            'email.email' => 'Email is invalid',
            'email.max' => 'Email is too long',
            'email.unique' => 'Email is already taken',
            'password.required' => 'Password is required',
            'password.min' => 'Password is too short',
        ]); // end of validation

        /**
         * Email Limit
         */
        $agent_count = EmailSMSLimitRate::where('status', 1)->where('owner_id', Auth::user()->id)->first();

        $agents = Agent::where('assined_for_customer_id', Auth::user()->id)->get();

        if (Auth::user()->user_type == 'Admin') {
            // create agent
            $user = new User; // create user
            $user->name = $request->name; // set name
            $user->slug = Str::slug($request->name); // set name
            $user->email = $request->email; // set email
            $user->password = Hash::make($request->password); // set password
            $user->active = 1; // set active
            $user->user_type = 'Agent'; // set user type
            $user->save(); // save user

            // create agent
            if ($user->save()) { // if agent is created
                $agent = new Agent; // create agent
                $agent->user_id = $user->id; // set user id
                $agent->assined_for_customer_id = Auth::id(); // set assined for customer id
                $agent->save(); // save agent

                // if your successfully insrted then decriment limit
                EmailSMSLimitRate::where('owner_id', Auth::user()->id)
                    ->decrement('agent', 1);

            } // end of if agent is created

            // show success message
            Alert::success(translate('Wow, Great!'), translate('Agent has been created successfully.')); // show success message
        } elseif ($agent_count->agent > 0) {
            // create agent
            $user = new User; // create user
            $user->name = $request->name; // set name
            $user->slug = Str::slug($request->name); // set name
            $user->email = $request->email; // set email
            $user->password = Hash::make($request->password); // set password
            $user->active = 1; // set active
            $user->user_type = 'Agent'; // set user type
            $user->save(); // save user

            // create agent
            if ($user->save()) { // if agent is created
                $agent = new Agent; // create agent
                $agent->user_id = $user->id; // set user id
                $agent->assined_for_customer_id = Auth::id(); // set assined for customer id
                $agent->save(); // save agent

                // if your successfully insrted then decriment limit
                EmailSMSLimitRate::where('owner_id', Auth::user()->id)
                    ->decrement('agent', 1);

            } // end of if agent is created

            // show success message
            Alert::success(translate('Wow, Great!'), translate('Agent has been created successfully.')); // show success message
        } else {

            Alert::error(translate('Sad, Error!'), translate('Agent Limit End.')); // show success message
        }

        return back(); // redirect to previous page
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\Agent  $agent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        /**
         * Validate the request
         */
        $this->validate($request, [ // validation rules
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
        ], [ // validation messages
            'name.required' => 'Name is required',
            'name.max' => 'Name is too long',
            'email.required' => 'Email is required',
            'email.email' => 'Email is invalid',
            'email.max' => 'Email is too long',
        ]); // end of validation

        // create agent
        $user = User::where('id', $id)->first(); // create user
        $user->name = $request->name; // set name
        $user->slug = Str::slug($request->name); // set name

        if ($request->email != $user->email) { // if email is changed

            // validate
            $this->validate($request, [ // validation rules
                'email' => 'unique:users',
            ], [ // validation messages
                'email.unique' => 'Email is already taken',
            ]); // end of validation

            $user->email = $request->email; // set email
        } // end of if email is changed

        if ($request->password != '') { // if password is changed

            // validate
            $this->validate($request, [ // validation rules
                'password' => 'min:6',
            ], [ // validation messages
                'password.min' => 'Password is too short',
            ]); // end of validation

            $user->password = Hash::make($request->password); // set password
        } // end of if password is changed

        $user->save(); // save user

        // show success message
        Alert::success(translate('Wow, Great!'), translate('Agent has been updated successfully.')); // show success message

        return back(); // redirect to previous page
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Agent  $agent
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $agent = User::where('id', $id)->with('agent')->first(); // find agent
        if ($agent->agent->delete()) { // if agent is deleted
            $agent->forceDelete(); // delete agent
        } // end of if agent is deleted
        Alert::success(translate('Wow, Great!'), translate('Agent has been deleted successfully.')); // show success message

        return back(); // redirect to previous page
    }

    /**
     * Restricted the specified resource from storage.
     *
     * @param  \App\Models\Agent  $agent
     * @return \Illuminate\Http\Response
     */
    public function restricted($id) {
        $agent = User::where('id', $id)->first(); // find agent
        if ($agent->active == 1) { // if agent is active
            $agent->active = 0; // set active to false
        } else { // if agent is not active
            $agent->active = 1; // set active to true
        } // end of if agent is not active
        $agent->save(); // save agent
        Alert::success(translate('Wow, Great!'), translate('Agent has been updated successfully.')); // show success message

        return back(); // redirect to previous page
    }

    // ENDS

}
