<?php

namespace App\Jobs\Payment;

use App\Models\User;
use App\Mail\InvoiceMail;
use App\Models\PlanPurchased;
use Illuminate\Bus\Queueable;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendInvoiceJob implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public PlanPurchased $plan, public User $user) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void {
        $plan = $this->plan;
        $user = $this->user;
        $path = invoice_path($plan->invoice);
        $pdf = Pdf::loadView('success.attachment_invoice', ['details' => $plan])->save($path);
        Mail::to($user->email)->send(new InvoiceMail($plan));
    }
}
