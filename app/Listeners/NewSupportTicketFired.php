<?php

namespace App\Listeners;

use App\Events\NewSupportTicketMail;
use Mail;
use Throwable;

class NewSupportTicketFired {
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct() {
        //
    }

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(NewSupportTicketMail $event) {

        try {
            $details = $event->details;
            Mail::send('mail.new_support_ticket', ['details' => $details], function ($message) use ($details) {
                $message->to($details['email']);
                $message->subject('Support Ticket #'.$details['ticket_no'].': '.$details['issue'].'');
            });
        } catch (Throwable $th) {
            //throw $th;
        }

    }
}
