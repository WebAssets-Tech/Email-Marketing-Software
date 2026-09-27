<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CampaignEmail extends Model {
    use HasFactory;

    public function scopeActive($query) {
        //
    }

    /**
     * !Deprecated for wrong convention from v.6.6.4
     */
    public function emails() {
        return $this->hasOne(EmailContact::class, 'id', 'email_id')->whereNotNull('email');
    }

    /**
     * * Excepted for right convention from v.6.6.4
     */
    public function email(): HasOne {
        return $this->hasOne(EmailContact::class, 'id', 'email_id')->whereNotNull('email');
    }

    public function phones() {
        return $this->hasOne(EmailContact::class, 'id', 'email_id')->whereNotNull('phone');
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
