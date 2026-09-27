<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class EmailContact extends Model
{
    use HasFactory, Notifiable;

    protected $guarded = [
        'id',
    ];
    protected $appends = [
        'phone_number'
    ];

    /*Check the email is active*/
    public function scopeActive($query)
    {
        return $query->where('trashed', 0)->where('blocked', 0);
    }

    /*Check the email is active*/
    public function scopeFavourite($query)
    {
        return $query->where('favourites', 1)->where('blocked', 0);
    }

    /*Check the email is active*/
    public function scopeBlocked($query)
    {
        return $query->where('blocked', 1);
    }

    /*Check the email is active*/
    public function scopeTrashedBin($query)
    {
        return $query->onlyTrashed()->where('trashed', 1);
    }

    /**
     * Agent
     */
    public function scopeHasAgent($query)
    {
        if (Auth::user()->user_type == 'Agent') {
            return $query->where('owner_id', agent_owner_id());
        }

        return $query->where('owner_id', Auth::user()->id);
    }
    public function getPhoneNumberAttribute(): string
    {
        if ($this->phone) {
            return '+' . $this->country_code . $this->phone;
        }
        return '';
    }
}
