<?php

namespace App\Mail;

use App\Models\Campaign;
use App\Models\EmailContact;
use App\Models\EmailTracker;
use App\Models\SenderEmailId;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CampaignTemplateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $tracker;

    public $campaign;

    public $senderEmail;

    public $emailContact;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Campaign|int $campaign, EmailTracker $tracker, ?SenderEmailId $senderEmail = null, EmailContact $emailContact,)
    {
        //find campaign if given id
        if (is_numeric($campaign)) {
            $campaign = Campaign::with([
                'campaign_attachment',
                'campaign_emails',
                'campaign_emails.emails',
                'emailService' => function ($q) {
                    $q->where('active', 1);
                },
                'emailService.sender_email',
            ])->find($campaign);
        }
        $this->campaign = $campaign;
        $this->tracker = $tracker;
        $this->senderEmail = $senderEmail ? $senderEmail : $campaign->senderEmails?->first();
        $this->emailContact = $emailContact;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $attachment = $this->campaign->campaign_attachment ? filePath($this->campaign->campaign_attachment->attachment) : null;
        if ($attachment) {
            $this->attach($attachment);
        }
        $content = replaceTags($this->campaign?->template?->html, $this->emailContact->toArray());

        return $this->subject($this->campaign->name)
            ->from($this->senderEmail->sender_email_address, $this->senderEmail->sender_name)
            ->cc($this->campaign->cc)
            ->bcc($this->campaign->bcc)
            ->view('mail.campaign.campaign-template-mail')
            ->with([
                'tracker' => $this->tracker,
                'content' => $content,
            ]);
    }
}
