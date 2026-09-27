<?php

namespace App\Exports;

use App\Models\EmailTracker;
use Maatwebsite\Excel\Concerns\FromCollection;

class CampaignReportExport implements FromCollection {
    public $id;

    public function __construct(int $id) {
        $this->id = $id;
    }

    public function collection() {
        return EmailTracker::where('campaign_id', $this->id)->get();
    }
}
