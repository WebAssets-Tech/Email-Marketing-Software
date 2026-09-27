<?php

namespace App\Console\Commands;

use App\Http\Controllers\SmsController;
use App\Models\ScheduleSms;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class SendSMS extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $campaignSMSs = ScheduleSms::where(function ($q) {
            $q->where('status', '=', ScheduleSms::PENDING);
            $q->where('scheduled_at', '<=', Carbon::now());
        })->with('campaign.smsTemplate')->get();

        foreach ($campaignSMSs as $campaign_info) {
            if ($campaign_info) {
                $smsControllerSendSms = new SmsController;
                $smsControllerSendSms->campaignSendSms(
                    $campaign_info->campaign_id,
                    $campaign_info->campaign->sms_template_id,
                    $campaign_info->campaign->sms_server_id
                );

                // then sent status
                $campaign_info->status = ScheduleSms::SENT;
                $campaign_info->sended_at = Carbon::now();
                $campaign_info->save();

            }
        }

        return 0;
    }
}
