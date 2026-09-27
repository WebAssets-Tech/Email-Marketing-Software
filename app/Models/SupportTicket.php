<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model {
    use HasFactory;

    protected $guarded = ['id'];

    //relationship between support ticker attachments and support ticket
    public function attachments() {
        return $this->hasMany(SupportTicketAttachment::class);
    }

    //relationship between support ticket and support ticket reply
    public function replies() {
        return $this->hasMany(SupportTicketReply::class);
    }

    //relationship between support ticket and user
    public function user() {
        return $this->belongsTo(User::class);
    }

    // ENDS
}
