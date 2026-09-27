<?php

namespace App\Http\Controllers;

use Auth;
use File;
use Session;
use Throwable;
use Carbon\Carbon;
use App\Models\Campaign;
use App\Models\SmsBuilder;
use App\Models\ScheduleSms;
use Illuminate\Support\Str;
use App\Models\BouncedEmail;
use App\Models\EmailContact;
use App\Models\EmailTracker;
use App\Models\Unsubscribed;
use Illuminate\Http\Request;
use App\Models\CampaignEmail;
use App\Models\ScheduleEmail;
use App\Models\EmailListGroup;
use App\Models\UserSentRecord;

/**version 2.0 */

use App\Models\EmailSMSLimitRate;
use App\Jobs\SendCampaignEmailJob;
use App\Models\CampaignAttachment;
use Aman\EmailVerifier\EmailChecker;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        set_time_limit(-1);
    }

    public function index()
    {
        try {
            if (templateCount() > 0 && smsTemplateCount() > 0) {
                $campaigns = Campaign::HasAgent()->latest()->paginate(10);

                return view('campaign.index', compact('campaigns'));
            } else {
                Alert::warning(translate('Warning'), translate('You have No Email Template & SMS Body.'));

                return redirect()->route('dashboard');
            }
        } catch (Throwable $th) {
            Alert::error(translate('Whoops'), translate('Something went wrong'));

            return back()->withErrors($th->getMessage());
        }
    }

    public function type($type)
    {
        if ($type == 'email') {
            $campaigns = Campaign::HasAgent()->where('type', 'email')->latest()->paginate(10);

            return view('campaign.email', compact('campaigns'));
        } else {
            $campaigns = Campaign::HasAgent()->where('type', 'sms')->latest()->paginate(10);
            $sms_templates = SmsBuilder::Active()->where('user_id', Auth::id())->get();

            return view('campaign.sms', compact('campaigns', 'sms_templates'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (templateCount() > 0 && smsTemplateCount() > 0) {
            return view('campaign.set_campaign');
        } else {
            Alert::warning(translate('Warning'), translate('You have No Email Template & SMS Body. Please Create An Email Template & SMS Body First.'));

            return redirect()->route('dashboard');
        }
    }

    /**
     * createType
     */
    public function createType($type)
    {
        if (templateCount() > 0) {
            if ($type == 'email') {
                return view('campaign.email.create.step1');
            } else {
                return view('campaign.sms.create.step1');
            }
        } else {
            Alert::warning(translate('Warning'), translate('You have No Email Template. Please Create An Email Template First.'));

            return back();
        }
    }

    /**
     * step1
     */
    public function step1Store(Request $request)
    {
        // return $request->all();
        if (env('DEMO_MODE') === 'YES') {
            Alert::warning('warning', 'This is demo purpose only');

            return back();
        }

        $type = $request->type;

        $step1 = new Campaign();
        $step1->owner_id = Auth::user()->id;
        $step1->name = $request->name;
        $step1->description = $request->description ?? null;
        $step1->bcc = $request->bcc ?? null;
        $step1->cc = $request->cc ?? null;
        $step1->smtp_server_id = $request->smtp_server_id;
        $step1->sms_server_id = $request->sms_server_id;

        if ($request->hasFile('attachment')) {
            $attachment = new CampaignAttachment;
            $attachment->session_id = Str::random(10);
            $attachment->campaign_id = $step1->id;
            $attachment->attachment = fileUpload($request->attachment, 'campaign_attachment');
            $attachment->save();

            // create session
            Session::put('attachment_session', $attachment->session_id);
        }

        if ($request->status = 1) {
            $step1->status = true;
        } else {
            $step1->status = false;
        }

        notify()->success(translate('Campaign Saved'));

        return $this->createStep2($step1, $type);
    }

    /**
     * step2
     */
    public function createStep2($step1, $type)
    {
        if (env('DEMO_MODE') === 'YES') {
            Alert::warning('warning', 'This is demo purpose only');

            return back();
        }

        try {
            if ($type == 'email') {
                return view('campaign.email.create.step2', compact('step1'));
            } else {
                $sms_templates = SmsBuilder::Active()->where('user_id', Auth::id())->get();

                return view('campaign.sms.create.step2', compact('step1', 'sms_templates'));
            }
        } catch (Throwable $th) {
            Alert::error(translate('Whoops'), translate('Something went wrong'));

            return redirect()->route('dashboard')->withErrors($th->getMessage());
        }
    }

    /**
     * step2Store
     */
    public function step2Store(Request $request)
    {
        // return $request->all();
        if (env('DEMO_MODE') === 'YES') {
            Alert::warning('warning', 'This is demo purpose only');

            return back();
        }

        $type = $request->type;

        try {
            if ($type == 'email') {
                $step2 = new Campaign;
                $step2->template_id = $request->template_id;
                $step2->owner_id = Auth::user()->id;
                $step2->name = $request->name;
                $step2->bcc = $request->bcc ?? null;
                $step2->cc = $request->cc ?? null;
                $step2->description = $request->description ?? null;
                $step2->status = true;
                $step2->smtp_server_id = $request->smtp_server_id;
                $step2->type = 'email';
                $step2->save();

                // this if condition solve without attachment campaign_id null
                if (Session::has('attachment_session')) {
                    $attachment = CampaignAttachment::where('session_id', Session::get('attachment_session'))->first();
                    $attachment->campaign_id = $step2->id;
                    $attachment->save();
                }

                // session destroy
                Session::forget('attachment_session');

                notify()->success(translate('Templated Saved'));

                return $this->step3($step2, $type);
            } else {
                $request->validate([
                    'sms_template_id' => 'required',
                ]);
                $step2 = new Campaign();
                $step2->sms_template_id = $request->sms_template_id;
                $step2->owner_id = Auth::user()->id;
                $step2->name = $request->name;
                $step2->sms_server_id = $request->sms_server_id;
                $step2->description = $request->description ?? null;
                $step2->status = true;
                $step2->type = 'sms';
                $step2->save();
                notify()->success(translate('Templated Saved'));

                return $this->step3($step2, $type);
            }
        } catch (Throwable $th) {
            Alert::error(translate('Whoops'), translate('Something went wrong'));

            return redirect()->route('dashboard')->withErrors($th->getMessage());
        }
    }

    /**
     * step3
     */
    public function step3($step2, $type)
    {
        if (env('DEMO_MODE') === 'YES') {
            Alert::warning('warning', 'This is demo purpose only');

            return back();
        }

        try {
            if ($type == 'email') {
                telling(route('campaign.index'), translate('New Email Campaign Created'));
                $campaign_id = $step2->id;

                return view('campaign.email.create.step3', compact('campaign_id'));
            } else {
                telling(route('campaign.index'), translate('New SMS Campaign Created'));
                $campaign_id = $step2->id;

                return view('campaign.sms.create.step3', compact('campaign_id'));
            }
        } catch (Throwable $th) {
            Alert::error(translate('Whoops'), translate('Something went wrong'));

            return redirect()->route('dashboard')->withErrors($th->getMessage());
        }
    }

    public function searchMail(Request $request)
    {
        $emails = EmailContact::where('email', 'LIKE', '%' . $request->value . '%')->orderBy('email')->paginate(20);

        $emailCollection = EmailContactResource::collection($emails);

        return response()->json($emailCollection);
    }

    /**
     * emails
     */
    public function emails()
    {
        try {
            return view('campaign.components.emails');
        } catch (Throwable $th) {
            Alert::error(translate('Whoops'), translate('Something went wrong'));

            return redirect()->route('dashboard')->withErrors($th->getMessage());
        }
    }

    /**
     * emailsStore
     */
    public function emailsStore(Request $request)
    {
        if (env('DEMO_MODE') === 'YES') {
            Alert::warning('warning', 'This is demo purpose only');

            return back();
        }

        $ids = $request->ids;
        $campaign_id = $request->campaign_id;
        $group_id = $request->groupIds;
        $emails = explode(',', $ids);

        if ($ids != null) {
            foreach ($emails as $email) {
                $checkMmails = CampaignEmail::where('campaign_id', $campaign_id)->where('email_id', $email)->first();
                if ($checkMmails == null) {
                    $campaign_email = new CampaignEmail();
                    $campaign_email->campaign_id = $campaign_id;
                    $campaign_email->email_id = $email;
                    $campaign_email->save();
                }
            }
        }

        $groups = Campaign::where('id', $campaign_id)->first();
        $groups->group_id = json_encode($request->groupIds);
        $groups->save();

        $groups_id = explode(',', $group_id);

        $emails_from_groups = EmailListGroup::whereIn('email_group_id', $groups_id)->with('emails')->get();

        foreach ($emails_from_groups as $emails_from_group) {
            if ($emails_from_group->email_id != null) {
                $campaign_emails = CampaignEmail::where('campaign_id', $campaign_id)->where('email_id', $emails_from_group->email_id)->first();

                if ($campaign_emails == null) {
                    $campaign_email = new CampaignEmail();
                    $campaign_email->campaign_id = $campaign_id;
                    $campaign_email->email_id = $emails_from_group->email_id;
                    $campaign_email->save();
                }
            }
        }

        return response()->json(['status' => true, 'message' => translate('Campaign Stored Successfully')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $checkType = Campaign::where('id', $id)->first();
        try {
            if ($checkType->type == 'email') {
                $edit_campaign = Campaign::where('id', $id)->with('campaign_emails')->first();

                return view('campaign.email.edit', compact('edit_campaign'));
            } else {
                $edit_campaign = Campaign::where('id', $id)->with('campaign_emails')->first();

                return view('campaign.sms.edit', compact('edit_campaign'));
            }
        } catch (Throwable $th) {
            Alert::error(translate('Whoops'), translate('Something went wrong'));

            return back()->withErrors($th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (env('DEMO_MODE') === 'YES') {
            Alert::warning('warning', 'This is demo purpose only');

            return back();
        }

        $ids = $request->ids;
        $campaign_id = $request->campaign_id ?? $id;
        // $group_id = $request->groupIds;
        $emails = explode(',', $ids);

        try {
            $update_campaign = Campaign::where('id', $campaign_id)->first();
            $update_campaign->owner_id = Auth::user()->id;
            $update_campaign->name = $request->name;

            $update_campaign->bcc = $request->bcc ?? null;
            $update_campaign->cc = $request->cc ?? null;

            $update_campaign->smtp_server_id = $request->smtp_server_id;
            $update_campaign->template_id = $request->template_id ?? null;
            $update_campaign->sms_template_id = $request->sms_template_id ?? null;
            $update_campaign->description = $request->description ?? null;

            if ($request->hasFile('new_attachment')) {
                $attachment = CampaignAttachment::where('campaign_id', $campaign_id)->first();

                if ($attachment == null) {
                    $attachment = new CampaignAttachment;
                    $attachment->campaign_id = $campaign_id;
                }

                $attachment->attachment = fileUpload($request->new_attachment, 'campaign_attachment');
                $attachment->save();
            }

            if ($request->status = 1) {
                $update_campaign->status = true;
            } else {
                $update_campaign->status = false;
            }

            $update_campaign->save();

            if ($request->check != null) {
                if ($ids != null) {
                    $delete_email = CampaignEmail::where('campaign_id', $id)->delete();
                    foreach ($emails as $email) {
                        $checkMmails = CampaignEmail::where('campaign_id', $campaign_id)->where('email_id', $email)->first();
                        if ($checkMmails == null) {
                            $campaign_email = new CampaignEmail();
                            $campaign_email->campaign_id = $campaign_id;
                            $campaign_email->email_id = $email;
                            $campaign_email->save();
                        }
                    }
                }
            }

            Alert::success(translate('Success'), translate('Campaign Updated'));

            return back();
        } catch (Throwable $th) {
            Alert::error(translate('Whoops'), translate('Something went wrong'));

            return back()->withErrors($th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (env('DEMO_MODE') === 'YES') {
            Alert::warning('warning', 'This is demo purpose only');

            return back();
        }

        try {
            Campaign::findOrFail($id)->delete();
            CampaignEmail::where('campaign_id', $id)->delete();
            ScheduleEmail::where('campaign_id', $id)->delete();
            CampaignAttachment::where('campaign_id', $id)->delete();
            Alert::success(translate('Success'), translate('Campaign Deleted'));

            return back();
        } catch (Throwable $th) {
            Alert::error(translate('Whoops'), translate('Something went wrong'));

            return redirect()->route('dashboard')->withErrors($th->getMessage());
        }
    }

    /** SEND EMAIL */
    public function campaignSendEmail($campaign_id, $template_id, $api_response = null)
    {
        if (env('DEMO_MODE') === 'YES') {
            Alert::warning('warning', 'This is demo purpose only');

            return back();
        }

        try {
            //Reducing previous queries into one.
            $campaign = Campaign::with([
                'user',
                'template',
                'campaign_attachment',
                'campaign_emails',
                'campaign_emails.emails',
                'emailService' => fn($q) => $q->where('active', 1),
            ])->find($campaign_id);
            //load sender_emails
            $campaign->load(['senderEmails' => fn($q) => $q->where('owner_id', $campaign->owner_id)]);

            //Assigning required variables
            $owner_id = $campaign->owner_id;
            $campaignEmails = $campaign->campaign_emails;
            $senderEmail = $campaign->senderEmails?->first();

            if ($campaignEmails->count() < 0) {
                Alert::error('Whoops', 'No Emails Found');

                return back();
            }

            if ($senderEmail == null) {
                Alert::warning('Hi,', translate('Please provide sender email address'));

                return back();
            }

            if (! emailLimitCheck(Auth::user()->id ?? $owner_id)) {

                Alert::error(translate('Whoops'), translate('Subscription Expired'));

                return back();
            } else {
                foreach ($campaignEmails as $campaignEmail) {
                    if (saas()) {
                        if (! user_email_limit_check(trimDomain(full_domain())) == 'HAS-LIMIT') {
                            return redirect()->route('saas.response.index', ['message' => 'You have reached your email limit. Please contact your administrator.']);
                        }
                        user_email_limit_decrement(trimDomain(full_domain())); // user_email_limit_decrement
                    }

                    SendCampaignEmailJob::dispatch($campaign, $campaignEmail, $schedule = null, $senderEmail);
                }
                campaignLog($campaign->id, $campaign->name, translate('campaign completed'));
                Alert::success(translate('Wow, Great !'), translate('Mailer Engine checked bounce emails'));

                return back();
            }
        } catch (Throwable $th) {
            if (config('app.debug')) {
                dd($th);
            }
            Alert::error(translate('Whoops'), translate('Something went wrong try again'));
            return back()->withErrors($th->getMessage());
        }
    }

    /**
     * EMAIL BOUNCER
     * !Deprecated from v6.6.4
     */
    public function emailBounce($campaignEmails, $campaign_id, $tracker_uuid, $api_response = null)
    {
        if (env('DEMO_MODE') === 'YES') {
            Alert::warning('warning', 'This is demo purpose only');

            return back();
        }

        try {
            foreach ($campaignEmails as $campaignEmail) {
                if ($campaignEmail->emails != null) {
                    /**
                     * Check bounce
                     */
                    // $bounced = app(EmailChecker::class)
                    //             ->checkEmail($campaignEmail->emails->email,'boolean'); // old version

                    $bounced = emailAddressVerify($campaignEmail->emails->email);
                    $bounce = new BouncedEmail;
                    // $bounce->bounce = $bounced['success']; // old version
                    $bounce->bounce = $bounced;
                    $bounce->owner_id = Auth::user()->id;
                    $bounce->email = $campaignEmail->emails->email;
                    $bounce->camapaign_id = $campaign_id;
                    $bounce->save();

                    /**
                     * Email sent record
                     */
                    $user_sent_mail_record = new UserSentRecord();
                    $user_sent_mail_record->owner_id = Auth::user()->id;
                    $user_sent_mail_record->type = 'email';
                    $user_sent_mail_record->save();

                    $tracker = EmailTracker::where('tracker', $tracker_uuid)->update([
                        'status' => $bounced,
                        // 'status' => $bounced['success']
                    ]);
                }
            }
            /**
             * Email Limit
             */
            $email_limit = EmailSMSLimitRate::HasAgent()
                ->first();
            /**
             * Decreament from limit
             */
            if ($email_limit->email > 0) {
                EmailSMSLimitRate::HasAgent()
                    ->decrement('email', count($campaignEmails));
            }
            /**
             * Check Current Limit
             */
            $current_email_limit = EmailSMSLimitRate::HasAgent()
                ->first();
            /**
             * Updating Due limit into Zero
             */
            if ($current_email_limit->email <= 0) {
                $current_email_limit->email = 0;
                $current_email_limit->save();
            }
            /**
             * CAMPAIGN LOG
             */
            campaignLog($campaign_id, getCampaignName($campaign_id)->name, translate(' campaign has been compeleted'));

            if ($api_response == null) {
                /**
                 * notify
                 */
                Alert::success(translate('Wow, Great !'), translate('Mailer Engine checked bounce emails'));

                return back();
            } else {
                return response()->json(['success' => true]);
            }
        } catch (Throwable $th) {
            Alert::error(translate('Whoops'), translate('Mailer Engine Crashed. Try Again'));

            return back()->withErrors($th->getMessage());
        }
    }

    /**VERSION 2.0 */

    // scheduleSendEmail
    public function scheduleSendEmail($campaign_id, $template_id)
    {
        if (env('DEMO_MODE') === 'YES') {
            Alert::warning('warning', 'This is demo purpose only');

            return back();
        }

        $campaign_name = Campaign::where('id', $campaign_id)
            ->first()
            ->name;

        $calendar = ScheduleEmail::HasAgent()
            ->with('campaign')
            ->get();

        return view('campaign.schedule.schedule', compact('campaign_id', 'campaign_name', 'template_id', 'calendar'));
    }

    // scheduleSendEmailStore
    public function scheduleSendEmailStore(Request $request, $campaign_id, $template_id)
    {
        if (env('DEMO_MODE') === 'YES') {
            Alert::warning('warning', 'This is demo purpose only');

            return back();
        }

        $date = Carbon::parse($request->date)->format('Y-m-d');
        $time = $request->time;
        $scheduled_at = $date . ' ' . $time;

        $schedule = new ScheduleEmail;
        $schedule->owner_id = Auth::user()->id;
        $schedule->campaign_id = $campaign_id;
        $schedule->scheduled_at = $scheduled_at;
        $schedule->status = 'PENDING';
        $schedule->created_at = Carbon::now();
        $schedule->save();

        Alert::success(translate('Great'), translate('Schedule is created'));

        return redirect()->route('campaign.schedule.emails');
    }

    /**scheduleSendEmails */
    public function scheduleSendEmails()
    {
        $schedules = ScheduleEmail::HasAgent()
            ->with('campaign')
            ->paginate(20);

        $calendar = ScheduleEmail::HasAgent()
            ->with('campaign')
            ->get();

        return view('campaign.schedule.index', compact('schedules', 'calendar'));
    }

    /**scheduleSendEmailDelete */
    public function scheduleSendEmailDelete($schedule_id)
    {
        if (env('DEMO_MODE') === 'YES') {
            Alert::warning('warning', 'This is demo purpose only');

            return back();
        }

        ScheduleEmail::HasAgent()
            ->where('id', $schedule_id)
            ->delete();

        Alert::success(translate('Deleted'), translate('Schedule is deleted'));

        return back();
    }

    /**scheduleSendEmailEdit */
    public function scheduleSendEmailEdit($schedule_id)
    {
        $schedule = ScheduleEmail::where('id', $schedule_id)
            ->with('campaign')
            ->first();

        $calendar = ScheduleEmail::where('id', $schedule_id)
            ->with('campaign')
            ->get();

        return view('campaign.schedule.edit', compact('schedule', 'calendar'));
    }

    /**scheduleSendEmailCancel */
    public function scheduleSendEmailCancel($schedule_id)
    {
        $schedule = ScheduleEmail::where('id', $schedule_id)
            ->first();
        $schedule->update([
            'status' => 'CANCEL',
        ]);

        return back();
    }

    public function scheduleSendEmailPending($schedule_id)
    {
        $schedule = ScheduleEmail::where('id', $schedule_id)
            ->first();
        $schedule->update([
            'status' => 'PENDING',
        ]);

        return back();
    }

    // scheduleSendEmailUpdate
    public function scheduleSendEmailUpdate(Request $request, $schedule_id)
    {
        if (env('DEMO_MODE') === 'YES') {
            Alert::warning('warning', 'This is demo purpose only');

            return back();
        }

        $date = Carbon::parse($request->date)->format('Y-m-d');
        $time = $request->time;
        $scheduled_at = $date . ' ' . $time;

        $schedule = ScheduleEmail::where('id', $schedule_id)->first();
        $schedule->scheduled_at = $scheduled_at;
        $schedule->updated_at = Carbon::now();
        $schedule->save();

        Alert::success(translate('Great'), translate('Schedule is updated'));

        return back();
    }

    /**VERSION 2.0::END */

    // SMS Schedule

    // scheduleSendSms
    public function scheduleSendSms($campaign_id, $template_id)
    {
        if (env('DEMO_MODE') === 'YES') {
            Alert::warning('warning', 'This is demo purpose only');

            return back();
        }

        $campaign_name = Campaign::where('id', $campaign_id)
            ->first()
            ->name;

        $calendar = ScheduleEmail::HasAgent()
            ->with('campaign')
            ->get();

        return view('campaign.schedule-sms.schedule', compact('campaign_id', 'campaign_name', 'template_id', 'calendar'));
    }

    // scheduleSendSmsStore
    public function scheduleSendSmsStore(Request $request, $campaign_id, $template_id)
    {
        if (env('DEMO_MODE') === 'YES') {
            Alert::warning('warning', 'This is demo purpose only');

            return back();
        }

        $date = Carbon::parse($request->date)->format('Y-m-d');
        $time = $request->time;
        $scheduled_at = $date . ' ' . $time;

        $schedule = new ScheduleSms;
        $schedule->owner_id = Auth::user()->id;
        $schedule->campaign_id = $campaign_id;
        $schedule->scheduled_at = $scheduled_at;
        $schedule->status = 'PENDING';
        $schedule->created_at = Carbon::now();
        $schedule->save();

        Alert::success(translate('Great'), translate('Schedule is created'));

        return back();
    }

    public function scheduleMailList()
    {

        $schedules = ScheduleSms::HasAgent()
            ->with('campaign')
            ->paginate(20);

        $calendar = ScheduleEmail::HasAgent()
            ->with('campaign')
            ->get();

        return view('campaign.schedule-sms.index', compact('schedules', 'calendar'));
    }

    /**scheduleSendEmailDelete */
    public function scheduleSendSmsDelete($schedule_id)
    {
        if (env('DEMO_MODE') === 'YES') {
            Alert::warning('warning', 'This is demo purpose only');

            return back();
        }

        ScheduleSms::HasAgent()
            ->where('id', $schedule_id)
            ->delete();

        Alert::success(translate('Deleted'), translate('Schedule is deleted'));

        return back();
    }

    /**scheduleSendEmailCancel */
    public function scheduleSendSmsCancel($schedule_id)
    {
        $schedule = ScheduleSms::where('id', $schedule_id)
            ->first();
        $schedule->update([
            'status' => 'CANCEL',
        ]);

        return back();
    }

    public function scheduleSendSmsPending($schedule_id)
    {
        $schedule = ScheduleSms::where('id', $schedule_id)
            ->first();
        $schedule->update([
            'status' => 'PENDING',
        ]);

        return back();
    }

    /**scheduleSendEmailEdit */
    public function scheduleSendSmsEdit($schedule_id)
    {
        $schedule = ScheduleSms::where('id', $schedule_id)
            ->with('campaign')
            ->first();

        $calendar = ScheduleSms::where('id', $schedule_id)
            ->with('campaign')
            ->get();

        return view('campaign.schedule-sms.edit', compact('schedule', 'calendar'));
    }

    // scheduleSendEmailUpdate
    public function scheduleSendSmsUpdate(Request $request, $schedule_id)
    {
        if (env('DEMO_MODE') === 'YES') {
            Alert::warning('warning', 'This is demo purpose only');

            return back();
        }

        $date = Carbon::parse($request->date)->format('Y-m-d');
        $time = $request->time;
        $scheduled_at = $date . ' ' . $time;

        $schedule = ScheduleSms::where('id', $schedule_id)->first();
        $schedule->scheduled_at = $scheduled_at;
        $schedule->updated_at = Carbon::now();
        $schedule->save();

        Alert::success(translate('Great'), translate('Schedule is updated'));

        return back();
    }
    /**
     * VERSION 4.2
     */

    /**
     * contactsEmails
     */
    public function contactsEmails(Request $request)
    {
        try {
            $query = $request->query('search');
            //code...
            $emails = EmailContact::HasAgent()
                ->whereNotNull('email')
                ->where(function ($q) use ($query) {
                    if ($query != null) {
                        $q->where('name', 'like', "%{$query}%");
                    }
                })
                ->orderBy('email')
                ->Active()
                ->latest()
                ->simplePaginate(20);

            return view('campaign.components.load_pages.emails', compact('emails'));
        } catch (Throwable $th) {
            return $th->getMessage();
        }
    }

    //  contactsSMS
    public function contactsSMS()
    {
        $emails = EmailContact::HasAgent()
            ->whereNotNull('phone')
            ->whereNotNull('country_code')
            ->Active()
            ->latest()
            ->simplePaginate(20);

        return view('campaign.components.load_pages.sms', compact('emails'));
    }

    /**
     * contactsEmailsEdit
     */
    public function contactsEmailsEdit($id)
    {
        $edit_campaign = Campaign::where('id', $id)->with('campaign_emails')->first();
        $emails = EmailContact::Active()->HasAgent()->whereNotNull('email')->latest()->simplePaginate(20);

        return view('campaign.components.load_pages.emails_edit', compact('edit_campaign', 'emails'));
    }

    /**
     * contactsEmailsEdit
     */
    public function contactsSMSEdit($id)
    {
        $edit_campaign = Campaign::where('id', $id)->with('campaign_emails')->first();
        $emails = EmailContact::Active()->HasAgent()->whereNotNull('phone')->whereNotNull('country_code')->latest()->simplePaginate(20);

        return view('campaign.components.load_pages.sms_edit', compact('edit_campaign', 'emails'));
    }

    /**
     * AJAX PAGINATION
     */
    public function contactsFetchDataEdit(Request $request, $id)
    {
        if ($request->ajax()) {
            $edit_campaign = Campaign::where('id', $id)->with('campaign_emails')->first();
            $emails = EmailContact::Active()->HasAgent()->whereNotNull('email')->latest()->simplePaginate(20);

            return view('campaign.components.load_pages.emails_edit', compact('edit_campaign', 'emails'));
        }
    }

    /**
     * AJAX PAGINATION
     */
    public function contactsFetchData(Request $request)
    {
        if ($request->ajax()) {
            $emails = EmailContact::HasAgent()
                ->whereNotNull('email')
                ->orderBy('email')
                ->Active()
                ->latest()
                ->simplePaginate(20);

            return view('campaign.components.load_pages.emails', compact('emails'));
        }
    }

    /**
     * VERSION 4.2::END
     */

    /**
     * VERSION 5.2.0
     */
    public function contactsUnsubscribe($campaign_id, $email_id)
    {
        $check_email_id = CampaignEmail::where('campaign_id', $campaign_id)
            ->where('email_id', $email_id)
            ->first();
        if ($check_email_id) {
            $check_email_id->delete();
            $unsubscribed = new Unsubscribed;
            $unsubscribed->campaign_id = $campaign_id;
            $unsubscribed->email_id = $email_id;
            $unsubscribed->save();

            if (request()->has('all')) {
                $contact = EmailContact::find($email_id);
                $contact->delete();
            }
            $text = 'Email unsubscribed';
            return view('unsubscribed.success', compact('text'));
            return 'Email unsubscribed';
        } else {
            $text = 'Email already unsubscribed';
            return view('unsubscribed.success', compact('text'));
        }
    }

    /**
     * VERSION 5.2.0::ENDS
     */

    /**
     * VERSION 6.3.6
     */
    public function pdf_remove($id)
    {
        if (env('DEMO_MODE') === 'YES') {
            Alert::warning('warning', 'This is demo purpose only');

            return back();
        }

        $pdf = CampaignAttachment::where('campaign_id', $id)->first();
        if ($pdf) {
            Storage::disk('public')->delete($pdf->attachment);
            $pdf->delete();
        }


        Alert::success(translate('Success'), translate('PDF removed'));

        return back();
    }

    //END

    /**
     * Get the value of owner_id
     */
    public function getOwner_id()
    {
        return $this->owner_id;
    }

    /**
     * Set the value of owner_id
     *
     * @return self
     */
    public function setOwner_id($owner_id)
    {
        $this->owner_id = $owner_id;

        return $this;
    }
}
