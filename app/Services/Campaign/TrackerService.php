<?php

declare(strict_types=1);

namespace App\Services\Campaign;

use App\Models\EmailTracker;
use Illuminate\Support\Str;

class TrackerService {
    /**
     * Creates a new tracker
     */
    public function create(int $emailId, int $campaignId, int $status = 0): EmailTracker {
        return EmailTracker::create([
            'tracker' => Str::uuid(),
            'email_id' => $emailId,
            'campaign_id' => $campaignId,
            'total_clicks' => 0,
            'status' => $status,
            'record' => 'NOT OPEN',
        ]);
    }
}
