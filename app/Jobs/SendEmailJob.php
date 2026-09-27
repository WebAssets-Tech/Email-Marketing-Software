<?php

namespace App\Jobs;

use App\Mail\TestMail;
use App\Models\EmailService;
use App\Services\Mailer\MultiMailer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendEmailJob implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $service;

    public $campaignEmails;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(EmailService $service, $campaignEmails) {
        $this->service = $service;
        $this->campaignEmails = $campaignEmails;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {

        // $service = getUserActiveEmailDetails(1);
        $message = (new TestMail)
            ->from('rasel@imjol.com', 'Rasel')
            ->subject('Test Connection Subject from Job Dispatch with delay');

        foreach ($this->campaignEmails as $key => $value) {
            MultiMailer::mail($this->service)
                ->to('mojahid@imjol.com')
                ->queue($message);
        }
    }
}
