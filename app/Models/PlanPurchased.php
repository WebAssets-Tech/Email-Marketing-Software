<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanPurchased extends Model {
    use HasFactory;

    public function scopeActive($query) {
        return $query->where('status', 1);
    }

    /**
     * Agent
     */
    public function scopeHasAgent($query) {
        if (Auth::check() && Auth::user()->user_type == 'Agent') {
            return $query->where('user_id', agent_owner_id());
        }

        return $query->where('user_id', Auth::id());
    }

    //END
}
