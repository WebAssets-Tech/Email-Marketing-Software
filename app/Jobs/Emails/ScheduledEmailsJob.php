<?php

namespace App\Jobs\Emails;

use App\Mail\TestMail;
use App\Services\Mailer\MultiMailer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ScheduledEmailsJob implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct() {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        $provider = getUserActiveEmailDetails(2);
        $message = (new TestMail)
            ->from($provider->sender_email?->sender_email_address)
            ->subject('Testing through job dispatch');
        MultiMailer::mail($provider)
            ->to('mojahid@imjol.com')
            ->send($message);
    }
}
