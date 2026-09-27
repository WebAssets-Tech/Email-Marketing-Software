<?php

namespace App\Jobs;

use App\Mail\CampaignTemplateMail;
use App\Models\BouncedEmail;
use App\Models\Campaign;
use App\Models\CampaignEmail;
use App\Models\EmailSMSLimitRate;
use App\Models\EmailTracker;
use App\Models\ScheduleEmail;
use App\Models\SenderEmailId;
use App\Models\UserSentRecord;
use App\Services\Campaign\TrackerService;
use App\Services\Mailer\MultiMailer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendCampaignEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $campaign;

    public $campaignEmail;

    public $tracker;

    public $schedule;

    public $senderEmail;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Campaign $campaign, CampaignEmail $campaignEmail, ?ScheduleEmail $schedule = null, ?SenderEmailId $senderEmail = null)
    {
        $this->campaign = $campaign;
        // $this->tracker = $tracker;
        $this->campaignEmail = $campaignEmail;
        $this->schedule = $schedule;
        $this->senderEmail = $senderEmail ? $senderEmail : $campaign->senderEmails?->first();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        if (!$this->campaignEmail->email) {
            return;
        }
        $this->tracker = (new TrackerService)->create($this->campaignEmail->email->id, $this->campaign->id);
        MultiMailer::mail($this->campaign->emailService)
            ->to($this->campaignEmail->email->email)
            ->send(new CampaignTemplateMail($this->campaign, $this->tracker, $this->senderEmail, $this->campaignEmail->email));

        if ($this->schedule !== null) {
            $this->schedule->update([
                'status' => ScheduleEmail::SENT,
                'sended_at' => now(),
            ]);
        }
        $this->updateEmailLimit($this->campaign->owner_id);
        $this->checkBounceEmail($this->campaignEmail, $this->campaign, $this->tracker);
    }

    public function checkBounceEmail(CampaignEmail $campaignEmail, Campaign $campaign, EmailTracker $tracker): void
    {
        /**
         * Check bounce
         */
        // $bounced = app(EmailChecker::class)
        //             ->checkEmail($campaignEmail->emails->email,'boolean'); // old version

        $bounced = emailAddressVerify($campaignEmail->email->email);
        $bounce = new BouncedEmail;
        // $bounce->bounce = $bounced['success']; // old version
        $bounce->bounce = $bounced;
        $bounce->owner_id = $campaign->owner_id;
        $bounce->email = $campaignEmail->emails->email;
        $bounce->camapaign_id = $campaign->id;
        $bounce->save();

        /**
         * Email sent record
         */
        $user_sent_mail_record = new UserSentRecord();
        $user_sent_mail_record->owner_id = $campaign->owner_id;
        $user_sent_mail_record->type = 'email';
        $user_sent_mail_record->save();

        $tracker->update([
            'status' => $bounced,
            // 'status' => $bounced['success']
        ]);
    }

    protected function updateEmailLimit(int $owner_id, ?int $count = 1)
    {
        $emailLimit = EmailSMSLimitRate::where('owner_id', $owner_id)
            ->where('status', 1)
            ->first();
        // $this->fail(json_decode($emailLimit));

        if ($emailLimit !== null) {
            // code...
            if ($emailLimit->email > 0) {
                $emailLimit->email -= $count;
            } else {
                $emailLimit->email = 0;
            }
            $emailLimit->save();
        }
    }
}
