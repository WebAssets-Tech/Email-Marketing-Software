<?php

namespace App\Http\Controllers;

use Hash;
use Throwable;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\EmailSMSLimitRate;
use App\Models\UserSentLimitPlan;
use RealRashid\SweetAlert\Facades\Alert;

class UserRateLimitController extends Controller
{
    public function index(Request $request)
    {
        try {
            if ($request->query('onlyTrashed') === '1') {
                $limits = User::onlyTrashed()
                    ->with('limit')
                    ->paginate(10);
            } else {
                $limits = User::with('limit')
                    ->paginate(10);
            }
            // return $limits;

            return view('rate_limit.index', compact('limits'));
        } catch (Throwable $th) {
            Alert::error(translate('Whoops'), translate('Something went wrong'));

            return back()->withErrors($th->getMessage());
        }
    }

    /**
     * MANAGE
     */
    public function manage($id)
    {
        try {
            $limit_user = EmailSMSLimitRate::where('owner_id', $id)->with('user')->first();

            return view('rate_limit.manage', compact('limit_user'));
        } catch (Throwable $th) {
            Alert::error(translate('Whoops'), translate('Something went wrong'));

            return back()->withErrors($th->getMessage());
        }
    }

    /**
     * UPDATE
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'email' => 'required',
            'sms' => 'required',
        ]);

        try {
            $update_limit = EmailSMSLimitRate::where('owner_id', $id)->first();
            $update_limit->email = $request->email;
            $update_limit->sms = $request->sms;
            $update_limit->agent = $request->agent;

            if ($request->has('duration')) {
                $update_limit->to = Carbon::create($update_limit->to)->addMonths($request->duration);
            }
            $update_limit->save();
            Alert::success(translate('Updated'), translate('Email Limit Updated'));
            return back();
        } catch (Throwable $th) {
            Alert::error(translate('Whoops'), translate('Something went wrong'));
            return back()->withErrors($th->getMessage());
        }
    }

    /**
     * CREATE
     */
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'user_type' => 'required',

        ]);

        try {
            $slug = Str::slug($request->name);
            $person = User::where('slug', $slug)->get();
            if ($person->count() > 0) {
                $slug1 = $slug . ($person->count() + 1);
            } else {
                $slug1 = $slug;
            }

            $new_user = new User();
            $new_user->name = $request->name;
            $new_user->slug = $slug . rand(1000, 100000); //slug
            $new_user->email = $request->useremail;
            $new_user->password = Hash::make($request->password);
            $new_user->user_type = $request->user_type;
            $new_user->active = true;
            $new_user->save();

            /**
             * User Sent Limit
             */
            $new_limit = new UserSentLimitPlan();
            $new_limit->owner_id = $new_user->id; // user_id
            $new_limit->plan_name = 'custom';
            $new_limit->limit = $request->email;
            $new_limit->from = Carbon::now();
            $new_limit->to = Carbon::now()->addMonths($request->duration);
            $new_limit->status = true;
            $new_limit->save();

            /**
             * EMAIL SMS LIMIT RATE
             */
            $email_sms_rate = new EmailSMSLimitRate();
            $email_sms_rate->owner_id = $new_user->id;
            $email_sms_rate->email = $request->email;
            $email_sms_rate->sms = $request->sms;
            $email_sms_rate->agent = $request->agent;
            $email_sms_rate->from = Carbon::now();
            $email_sms_rate->to = Carbon::now()->addMonths($request->duration);
            $email_sms_rate->status = true;
            $email_sms_rate->save();

            Alert::success(translate('Success'), translate('User Limit Created'));
            telling(route('limit.index'), $new_user->name . translate('User Limit Created'));

            return back();
        } catch (Throwable $th) {
            Alert::error(translate('Whoops'), translate('Something went wrong'));

            return back()->withErrors($th->getMessage());
        }
    }

    /**
     * Destroy
     */
    public function destroy($id)
    {
        try {
            $user = User::find($id);
            $user->delete();
            Alert::success(translate('Success'), translate('User Limit Deleted'));
            telling(route('limit.index'), translate('User Limit Deleted'));

            return back();
        } catch (Throwable $th) {
            Alert::error(translate('Whoops'), translate('Something went wrong'));

            return back()->withErrors($th->getMessage());
        }
    }

    public function restore($id)
    {
        try {
            User::withTrashed()->find($id)->restore();
            Alert::success(translate('Success'), translate('User Limit Restored'));
            telling(route('limit.index'), translate('User Limit Restored'));
            return back();
        } catch (Throwable $th) {
            Alert::error(translate('Whoops'), translate('Something went wrong'));

            return back()->withErrors($th->getMessage());
        }
    }

    public function destroyForever(User $user)
    {
        $name = $user->name;
        try {
            $user->forceDelete();
            Alert::success(translate('Success'), translate('User Limit Deleted Forever'));
            telling(route('limit.index'), translate('User Limit Deleted Forever'));
            return back();
        } catch (Throwable $th) {
            Alert::error(translate('Whoops'), $name . translate('Something went wrong'));
            return back()->withErrors($th->getMessage());
        }
    }

    /**
     * login_as
     */
    public function login_as($id)
    {
        try {
            $user = User::find($id);
            auth()->login($user);
            Alert::success(translate('Success'), translate('Login Successful'));
            return redirect()->route('dashboard');
        } catch (Throwable $th) {
            Alert::error(translate('Whoops'), translate('Something went wrong'));
            return back()->withErrors($th->getMessage());
        }
    }
    //END
}
