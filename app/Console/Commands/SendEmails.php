<?php

namespace App\Console\Commands;

use Aman\EmailVerifier\EmailChecker;
use App\Jobs\Emails\ScheduledEmailsJob;
use App\Jobs\SendCampaignEmailJob;
use App\Models\BouncedEmail;
use App\Models\Campaign;
use App\Models\CampaignLog;
use App\Models\EmailSMSLimitRate;
use App\Models\EmailTracker;
use App\Models\ScheduleEmail;
use App\Models\User;
use App\Models\UserSentRecord;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Log;
use Mail;

class SendEmails extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will manage schedule emails';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        // ScheduledEmailsJob::dispatch()->delay(now()->addMinutes(1));
        // your schedule code
        $schedules = ScheduleEmail::with(['campaign' => function ($q) {
            $q->with([
                'template',
                'user',
                'campaign_attachment',
                'campaign_emails',
                'campaign_emails.emails',
                'emailService' => fn ($q) => $q->where('active', 1),
            ]);
        }])->where(function ($q) {
            $q->where('status', '=', ScheduleEmail::PENDING);
            $q->where('scheduled_at', '>=', Carbon::now());
        })->get();

        if ($schedules->count() > 0) {
            foreach ($schedules as $schedule) {
                $schedule->status = ScheduleEmail::QUEUED;
                $schedule->save();
                $campaign = $schedule->campaign;
                //load sender_emails
                $campaign->load(['senderEmails' => fn ($q) => $q->where('owner_id', $campaign->owner_id)]);
                $senderEmail = $campaign->senderEmails?->first();

                //Assigning required variables
                $campaignEmails = $campaign->campaign_emails;

                foreach ($campaignEmails as $campaignEmail) {
                    if (saas()) {
                        if (! user_email_limit_check(trimDomain(full_domain())) == 'HAS-LIMIT') {
                            throw new Exception('You have reached your email limit. Please contact your administrator.');
                        }
                        user_email_limit_decrement(trimDomain(full_domain())); // user_email_limit_decrement
                    }
                    SendCampaignEmailJob::dispatch($campaign, $campaignEmail, $schedule, $senderEmail)->delay(Carbon::parse($schedule->scheduled_at, $campaign->user?->timezone));
                }
            }
        }
    }

    // /**
    //  * EMAIL BOUNCER
    //  */
    // public function emailBounce($campaignEmails, $campaign_id, $tracker_uuid) {
    //     if (env('DEMO_MODE') === 'YES') {
    //         echo 'This is demo purpose only';

    //         return false;
    //     }

    //     $owner_id = ScheduleEmail::where('campaign_id', $campaign_id)->first()->owner_id;

    //     foreach ($campaignEmails as $campaignEmail) {
    //         if ($campaignEmail->emails != null) {
    //             /**
    //              * Check bounce
    //              */
    //             // $bounced = app(EmailChecker::class)
    //             //             ->checkEmail($campaignEmail->emails->email,'boolean'); // old version

    //             $bounced = emailAddressVerify($campaignEmail->emails->email);
    //             $bounce = new BouncedEmail();
    //             // $bounce->bounce = $bounced['success']; // old version
    //             $bounce->bounce = $bounced;
    //             $bounce->owner_id = $owner_id;
    //             $bounce->email = $campaignEmail->emails->email;
    //             $bounce->camapaign_id = $campaign_id;
    //             $bounce->save();
    //             /**
    //              * Email sent record
    //              */
    //             $user_sent_mail_record = new UserSentRecord();
    //             $user_sent_mail_record->owner_id = $owner_id;
    //             $user_sent_mail_record->type = 'email';
    //             $user_sent_mail_record->save();

    //             $tracker = EmailTracker::where('tracker', $tracker_uuid)->update([
    //                 'status' => $bounced,
    //                 // 'status' => $bounced['success']
    //             ]);
    //         }
    //     }
    //     /**
    //      * Email Limit
    //      */
    //     $email_limit = EmailSMSLimitRate::where('owner_id', $owner_id)
    //         ->first();
    //     /**
    //      * Decreament from limit
    //      */
    //     if ($email_limit->email > 0) {
    //         EmailSMSLimitRate::where('owner_id', $owner_id)
    //             ->decrement('email', count($campaignEmails));
    //     }
    //     /**
    //      * Check Current Limit
    //      */
    //     $current_email_limit = EmailSMSLimitRate::where('owner_id', $owner_id)
    //         ->first();
    //     /**
    //      * Updating Due limit into Zero
    //      */
    //     if ($current_email_limit->email <= 0) {
    //         $current_email_limit->email = 0;
    //         $current_email_limit->save();
    //     }

    //     /**
    //      * CAMPAIGN LOG
    //      */
    //     $campaignLog = new CampaignLog();
    //     $campaignLog->owner_id = $owner_id;
    //     $campaignLog->campaign_id = $campaign_id;
    //     $campaignLog->campaign_name = getCampaignName($campaign_id)->name;
    //     $campaignLog->message = translate(' campaign has been compeleted') ?? null;
    //     $campaignLog->save();

    //     return $this->handle();
    // }
}
