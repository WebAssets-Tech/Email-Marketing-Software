<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model {
    use HasFactory;

    protected $guarded = [];

    public function scopeActive($query) {
        return $query->where('status', 1);
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

    /**
     * Sanitize dummy text and provide clean professional description
     */
    public function getDescriptionAttribute($value)
    {
        $clean = trim(strip_tags($value ?? ''));
        if (empty($clean) || str_contains(strtolower($clean), 'lorem') || str_contains(strtolower($clean), 'dummy text')) {
            $name = strtolower($this->name ?? '');
            if (str_contains($name, 'free') || (float) ($this->price ?? 0) <= 0) {
                return '<p>Free starter plan to kickstart your email broadcasts, test SMTP relays, and discover core features.</p>';
            } elseif (str_contains($name, 'year') || (int) ($this->duration ?? 0) >= 12) {
                return '<p>Enterprise-grade volume, maximum inbox deliverability, and dedicated team support at best savings.</p>';
            } elseif (str_contains($name, 'pro')) {
                return '<p>Advanced throughput with multi-relay SMTP balancing and team sub-agent collaboration tools.</p>';
            } else {
                return '<p>Perfect for growing businesses running regular campaigns with reliable high-speed email delivery.</p>';
            }
        }
        return $value;
    }

    public function getCleanDescriptionAttribute(): string
    {
        return trim(strip_tags($this->description ?? ''));
    }
}
