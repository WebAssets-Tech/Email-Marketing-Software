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
        return $query->where('status', 1)->where('owner_id', Auth::user()->id);
    }

    /**
     * Active
     */
    public function scopeExpiredCheck($query) {
        return $query->where('status', 0)->where('owner_id', Auth::user()->id);
    }

    /**
     * Expired
     */
    public function scopeUserCheck($query) {
        return $query->where('owner_id', Auth::user()->id);
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
