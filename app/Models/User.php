<?php

namespace App\Models;

use Auth;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail {
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * personal
     */
    public function personal() {
        return $this->hasOne(PersonalInformation::class, 'user_id', 'id');
    }

    public function limit() {
        return $this->hasOne(EmailSMSLimitRate::class, 'owner_id', 'id');
    }

    public function agent() {
        return $this->hasOne(Agent::class, 'user_id', 'id');
    }

    public function agents() {
        return $this->hasMany(Agent::class, 'user_id', 'id');
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

    public function campaigns() {
        return $this->hasMany(Campaign::class, 'owner_id');
    }
}
