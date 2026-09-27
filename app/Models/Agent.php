<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model {
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * relation with user
     */
    public function user() {
        return $this->belongsTo(User::class);
    }
    // ends
}
