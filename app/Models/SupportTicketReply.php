<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicketReply extends Model {
    use HasFactory;

    protected $guarded = ['id'];

    public function reply_attachments() {
        return $this->hasMany(SupportTicketReplyAttachment::class);
    }

    public function support_ticket() {
        return $this->hasOne(SupportTicket::class, 'id', 'support_ticket_id');
    }
}
