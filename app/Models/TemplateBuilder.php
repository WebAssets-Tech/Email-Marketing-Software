<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateBuilder extends Model {
    use HasFactory;

    protected $guarded = ['id'];

    public function scopePublished($query) {
        return $query->where('publish', true);
    }

    public function scopeDefault($query) {
        return $query->where('default', true);
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
