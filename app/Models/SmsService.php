<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SmsService extends Model {
    use HasFactory;

    protected $guarded = ['id'];

    public function sms_sender_id() {
        return $this->hasOne(SmsSenderId::class, 'sms_service_id', 'id');
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

    public function senders(): HasMany {
        return $this->hasMany(SmsSenderId::class, 'sms_service_id');
    }

    public function sender(): HasOne {
        return $this->senders()->where('owner_id', auth()->id())->one();
    }
}
