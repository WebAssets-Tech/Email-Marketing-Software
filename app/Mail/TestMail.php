<?php

namespace App\Mail;

use App\Models\EmailService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestMail extends Mailable {
    use Queueable, SerializesModels;

    public function __construct(public EmailService $emailService) {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        return $this->view('mail.test')
            ->from($this->emailService->sender_email?->sender_email_address, $this->emailService->sender_email?->sender_name)
            ->subject('Test Connection Subject');

    }
}
