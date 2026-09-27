<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Autoresponder extends Model {
    use HasFactory;

    public function campaign() {
        return $this->hasOne(Campaign::class, 'id', 'campaign_id');
    }

    public function autoresponder_contacts() {
        return $this->hasMany(AutoresponderContacts::class, 'autoresponder_id', 'id');
    }

    public function autoresponder_templates() {
        return $this->hasMany(AutoresponderTemplate::class, 'autoresponder_id', 'id');
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
}
