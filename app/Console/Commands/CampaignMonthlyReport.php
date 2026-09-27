<?php

namespace App\Console\Commands;

use App\Mail\CampaignMonthlyReportMail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CampaignMonthlyReport extends Command {
    /**
     * The name and signature of the console command.
     *monthly report campaign
     *
     * @var string
     */
    protected $signature = 'campaign:report';

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
        $users = User::where('active', 1)->get();
        if ($users->count() > 0) {
            foreach ($users as $user) {
                Mail::to($user->email)->send(new CampaignMonthlyReportMail());
            }
        }

        return 0;
    }
}
