<?php

namespace App\Services\Campaign;

use App\Mail\CampaignTemplateMail;
use App\Models\Campaign;
use App\Models\EmailTracker;
use App\Services\Mailer\MultiMailer;
use Illuminate\Support\Facades\Mail;
use SendGrid\Mail\Attachment;

class CampaignService {
    /**
     * **Send Email through campaign**
     * *Query campaign like this*
     * ```php
     * $campaign = Campaign::with([
     *     'template',
     *     'campaign_attachment',
     *     'campaign_emails',
     *     'campaign_emails.emails',
     *     'emailService' => fn ($q) => $q->where('active', 1)])
     *     ->find($campaign_id);
     * //load sender_emails separately
     * $campaign->load(['emailService.sender_email' => fn ($q) => $q->where('owner_id', $campaign->owner_id)]);
     * ```
     *
     * @param  string|array  $recipients
     * @return void
     */
    public static function sendEmail($recipients, Campaign $campaign, EmailTracker $tracker) {

        MultiMailer::mail($campaign->emailService)
            ->to($recipients)->queue(new CampaignTemplateMail($campaign, $tracker));
        // Mail::send('template_builder.template-detail', $data, function ($message) use ($cc, $bcc, $subject, $campaignEmail, $get_sender_email_address, $attachment) {
        //     $message->to($campaignEmail->emails->email)
        //         ->setFrom(
        //             [$get_sender_email_address->sender_email_address => $get_sender_email_address->sender_name]
        //         )
        //         ->setSubject($subject)
        //         ->attach(Swift_Attachment::fromPath($attachment));
        //     if (!empty($cc)) {
        //         $message->cc($cc, $cc);
        //     } elseif (!empty($bcc)) {
        //         $message->bcc($bcc, $bcc);
        //     } elseif (!empty($cc) && !empty($bcc)) {
        //         $message->cc($cc, $cc)->bcc($bcc, $bcc);
        //     } else {
        //     }
        // });
    }
}
