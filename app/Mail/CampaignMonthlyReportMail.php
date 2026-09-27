<?php

namespace App\Mail;

use App\Models\EmailTracker;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CampaignMonthlyReportMail extends Mailable {
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct() {
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        $campaigns = EmailTracker::groupBy('campaign_id')
            ->get();

        return $this->subject('Hello Your Monthly Report')
            ->view('email-view', compact('campaigns'));
    }
}
