<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailSMSLimitRate extends Model {
    use HasFactory;

    protected $guarded = [];

    /**
     * Active
     */
    public function scopeActive($query) {
        if (Auth::check() && Auth::user()->user_type == 'Agent') {
            return $query->where('status', 1)->where('owner_id', agent_owner_id());
        }
        return $query->where('status', 1)->where('owner_id', Auth::id());
    }

    /**
     * Expired Check
     */
    public function scopeExpiredCheck($query) {
        if (Auth::check() && Auth::user()->user_type == 'Agent') {
            return $query->where('status', 0)->where('owner_id', agent_owner_id());
        }
        return $query->where('status', 0)->where('owner_id', Auth::id());
    }

    /**
     * User Check
     */
    public function scopeUserCheck($query) {
        if (Auth::check() && Auth::user()->user_type == 'Agent') {
            return $query->where('owner_id', agent_owner_id());
        }
        return $query->where('owner_id', Auth::id());
    }

    /**
     * USER
     */
    public function user() {
        return $this->hasOne(User::class, 'id', 'owner_id');
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
