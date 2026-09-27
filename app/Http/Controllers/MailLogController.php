<?php

namespace App\Http\Controllers;

use App\Models\BouncedEmail;
use App\Models\MailLog;

class MailLogController extends Controller {
    public function index() {
        $logs = MailLog::latest()->paginate(20);

        return view('mail_logs.index', compact('logs'));
    }

    public function logs() {
        $logs = BouncedEmail::HasAgent()->latest()->paginate(20);

        return view('mail_logs.logs', compact('logs'));
    }
    //END
}
