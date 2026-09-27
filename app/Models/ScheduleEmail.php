<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleEmail extends Model {
    use HasFactory;

    /**
     * STATUS: PENDING
     */
    const PENDING = 'PENDING';

    /**
     * STATUS: SENDED
     */
    const SENT = 'SENT';

    /**
     * STATUS: QUEUED
     */
    const QUEUED = 'QUEUED';

    /**
     * Reset the timestamps value
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['campaign_id', 'scheduled_at', 'sended_at', 'status'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['scheduled_at', 'sended_at'];

    /**
     * campaign
     */
    public function campaign() {
        return $this->hasOne(Campaign::class, 'id', 'campaign_id');
    }

    /**
     * Agent
     */
    public function scopeHasAgent($query) {
        if (Auth::user()->user_type == 'Agent') {
            return $query->where('owner_id', agent_owner_id());
        }

        return $query->where('owner_id', Auth::user()->id);
    }

    // ENDS
}
