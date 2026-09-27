<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailGroup extends Model {
    use HasFactory;

    protected $guarded = ['id'];

    public function scopeActive($query) {
        return $query->where('status', 1);
    }

    public function email_groups() {
        return $this->hasMany(EmailListGroup::class, 'email_group_id', 'id')->where('owner_id', Auth::user()->id);
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
