<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\SubscriptionPlan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CouponController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('coupon.index'); // Coupon index page
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $this->validate($request, [ // Validate the request
            'code' => 'required|unique:coupons',
            'discount_amount' => 'required|numeric|min:0',
            'type' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',

        ], [
            'code.required' => 'Code is required',
            'code.unique' => 'Code already exists',
            'discount_amount.required' => 'Discount amount is required',
            'discount_amount.numeric' => 'Discount amount must be numeric',
            'discount_amount.min' => 'Discount amount must be greater than 0',
            'type.required' => 'Type is required',
            'start_date.required' => 'Start date is required',
            'end_date.required' => 'End date is required',
            'start_time.required' => 'Start time is required',
            'end_time.required' => 'End time is required',
        ]);

        $start_date = Carbon::parse($request->start_date)->format('Y-m-d'); // Format the start date
        $start_time = $request->start_time; // Get the start time
        $start_scheduled_at = $start_date.' '.$start_time; // Combine the start date and time

        $end_date = Carbon::parse($request->end_date)->format('Y-m-d'); // Format the end date
        $end_time = $request->end_time; // Get the end time
        $end_scheduled_at = $end_date.' '.$end_time; // Combine the end date and time

        $coupon = new Coupon; // Create a new coupon
        $coupon->code = $request->code; // Set the code
        $coupon->type = $request->type; // Set the type
        $coupon->date_from = $start_scheduled_at; // Set the start date and time
        $coupon->date_to = $end_scheduled_at; // Set the end date and time
        $coupon->discount_amount = $request->discount_amount; // Set the discount amount
        $coupon->minimum_purchase = $request->minimum_purchase; // Set the minimum purchase
        $coupon->maximum_usage = $request->maximum_usage; // Set the maximum usage

        if ($request->status == 1) { // If the status is 1
            $coupon->status = 1; // Set the status to 1
        } else { // Else
            $coupon->status = 0; // Set the status to 0
        } // End if

        $coupon->save(); // Save the coupon

        notify()->success($request->code.' '.translate('Coupon created successfully'));

        return back(); // Return back to the previous page
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $coupon = Coupon::where('id', $id)->first(); // get first coupon by id

        return view('coupon.show', compact('coupon')); // Show the coupon edit page
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $this->validate($request, [ // Validate the request
            'code' => 'required',
            'discount_amount' => 'required|numeric|min:0',
            'type' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',

        ], [
            'code.required' => 'Code is required',
            'code.unique' => 'Code already exists',
            'discount_amount.required' => 'Discount amount is required',
            'discount_amount.numeric' => 'Discount amount must be numeric',
            'discount_amount.min' => 'Discount amount must be greater than 0',
            'type.required' => 'Type is required',
            'start_date.required' => 'Start date is required',
            'end_date.required' => 'End date is required',
            'start_time.required' => 'Start time is required',
            'end_time.required' => 'End time is required',
        ]);

        $start_date = Carbon::parse($request->start_date)->format('Y-m-d'); // Format the start date
        $start_time = $request->start_time; // Get the start time
        $start_scheduled_at = $start_date.' '.$start_time; // Combine the start date and time

        $end_date = Carbon::parse($request->end_date)->format('Y-m-d'); // Format the end date
        $end_time = $request->end_time; // Get the end time
        $end_scheduled_at = $end_date.' '.$end_time; // Combine the end date and time

        $coupon = Coupon::where('id', $id)->first(); // get first coupon by id
        $coupon->code = $request->code; // Set the code
        $coupon->type = $request->type; // Set the type
        $coupon->date_from = $start_scheduled_at; // Set the start date and time
        $coupon->date_to = $end_scheduled_at; // Set the end date and time
        $coupon->discount_amount = $request->discount_amount; // Set the discount amount
        $coupon->minimum_purchase = $request->minimum_purchase; // Set the minimum purchase
        $coupon->maximum_usage = $request->maximum_usage; // Set the maximum usage

        if ($request->status == 1) { // If the status is 1
            $coupon->status = 1; // Set the status to 1
        } else { // Else
            $coupon->status = 0; // Set the status to 0
        } // End if

        $coupon->save(); // Save the coupon

        notify()->success($request->code.' '.translate('Coupon updated successfully'));

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $coupon = Coupon::where('id', $id)->first(); // get first coupon by id
        $coupon->delete(); // delete coupon

        notify()->success($coupon->code.' '.translate('Coupon deleted successfully'));

        return back();
    }

    /**
     * apply_coupon
     */
    public function apply_coupon(Request $request) {
        $this->validate($request, [ // Validate the request...
            'code' => 'required',
        ], [
            'code.required' => 'Coupon code is required',
        ]);

        // get coupon by code
        $coupon = Coupon::where('code', $request->code)->first(); // get first coupon by code

        // Maximum usage check
        if ($coupon->maximum_usage != 0 || $coupon->maximum_usage != null) { // if maximum usage is not 0 or null
            $usage_count = $coupon->usage_count; // get coupon usage count
            if ($usage_count >= $coupon->maximum_usage) { // if usage count is greater than or equal to maximum usage
                notify()->error(translate('Coupon usage limit exceeded'));

                return back();
            }
        }

        // Minimum purchase check
        if ($coupon->minimum_purchase != 0 || $coupon->minimum_purchase != null) { // if minimum purchase is set

            // Check Price Here ---------------------------- //
            $subscription_plan = SubscriptionPlan::where('id', $request->subscription_id)->first(); // get subscription plan
            // Check Price Here ---------------------------- //

            if ($subscription_plan->price < $coupon->minimum_purchase) { // if subscription plan price is less than minimum purchase
                notify()->error(translate('Minimum purchase amount is').' '.$coupon->minimum_purchase); // minimum purchase amount is

                return back();
            }
        }

        // Apply Coupon
        if ($coupon) {
            if ($coupon->status == 1) { // if coupon is active
                $now = Carbon::now(); // get current date and time
                $start_date = Carbon::parse($coupon->date_from)->format('Y-m-d'); // get start date
                $start_time = Carbon::parse($coupon->date_from)->format('H:i:s'); // get start time
                $start_scheduled_at = $start_date.' '.$start_time; // combine start date and time
                $end_date = Carbon::parse($coupon->date_to)->format('Y-m-d'); // get end date
                $end_time = Carbon::parse($coupon->date_to)->format('H:i:s'); // get end time
                $end_scheduled_at = $end_date.' '.$end_time; // combine end date and time
                if ($now->between($start_scheduled_at, $end_scheduled_at)) { // if coupon is active
                    $coupon->usage_count = $coupon->usage_count + 1; // increase usage count
                    $coupon->save(); // save coupon

                    // Calculate Price Here ---------------------------- //

                    // calculate total price after coupon
                    $subscription = SubscriptionPlan::where('id', $request->subscription_id)->first(); // get subscription plan

                    // for percentage
                    $coupon_amount = $subscription->price * ($coupon->discount_amount / 100); // discount amount

                    // Calculate Price Here::END ----------------------- //

                    // Session for coupon
                    session()->put('coupon', $coupon); // store coupon in session
                    session()->put('coupon_amount', $coupon_amount); // store coupon amount in session

                    notify()->success($request->code.' '.translate('Coupon applied successfully'));

                    return back();
                } else { // if coupon is not active
                    // Session Destroy for coupon
                    session()->forget('coupon'); // destroy coupon from session
                    session()->forget('coupon_amount'); // destroy coupon amount from session

                    notify()->error($request->code.' '.translate('Coupon Expired'));

                    return back();
                } // end of if coupon is active
            } else { // if coupon is not active
                // Session Destroy for coupon
                session()->forget('coupon'); // destroy coupon from session
                session()->forget('coupon_amount'); // destroy coupon amount from session

                notify()->error($request->code.' '.translate('Coupon is not valid'));

                return back();
            }
        } else { // if coupon is not valid
            // Session Destroy for coupon
            session()->forget('coupon'); // destroy coupon from session

            notify()->error($request->code.' '.translate('Coupon is not valid'));

            return back();
        } // end of if coupon
    }
}
