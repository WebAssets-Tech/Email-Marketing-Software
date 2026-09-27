<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTracker extends Model {
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    public function campaign_name() {
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

    // END
}
