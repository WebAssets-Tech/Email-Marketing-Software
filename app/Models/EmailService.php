<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailService extends Model {
    use HasFactory;

    protected $guarded = ['id'];

    public function scopeActive($query) {
        // return $query->where('active', 1)->where('owner_id', Auth::user()->id);
    }

    /**
     * !Deprecated from v6.6.4
     * * Use senderEmails instead
     *  VERSION 3
     * */
    public function sender_email() {
        return $this->hasOne(SenderEmailId::class, 'email_service_id', 'id');
    }

    /**
     * *From v6.7.1
     */
    public function senderEmails() {
        return $this->hasMany(SenderEmailId::class, 'email_service_id', 'id');
    }

    public function sender_email_without_auth() {
        return $this->hasOne(SenderEmailId::class, 'email_service_id', 'id');
    }
    /** VERSION 3::END */

    /**
     * Agent
     */
    public function scopeHasAgent($query) {
        if (Auth::user()->user_type == 'Agent') {
            return $query->where('owner_id', agent_owner_id());
        }

        return $query->where('owner_id', Auth::user()->id);
    }

    //Domains for mailgun provider

    public function domain() {
        return $this->hasOne(Domain::class)->where('user_id', auth()->id());
    }
}
