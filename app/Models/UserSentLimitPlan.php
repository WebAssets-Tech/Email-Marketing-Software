<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSentLimitPlan extends Model {
    use HasFactory;

    public function scopeActive($query) {
        if (Auth::check() && Auth::user()->user_type == 'Agent') {
            return $query->where('owner_id', agent_owner_id())->where('status', 1);
        }
        return $query->where('owner_id', Auth::id())->where('status', 1);
    }

    public function scopeUser($query) {
        if (Auth::check() && Auth::user()->user_type == 'Agent') {
            return $query->where('owner_id', agent_owner_id());
        }
        return $query->where('owner_id', Auth::id());
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

    //END
}
