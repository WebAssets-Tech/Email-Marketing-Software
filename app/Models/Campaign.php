<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Campaign extends Model {
    use HasFactory;

    public function scopeActive($query) {
        return $query->where('status', 1);
    }

    public function template() {
        return $this->HasOne(TemplateBuilder::class, 'id', 'template_id');
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    public function smsTemplate() {
        return $this->HasOne(SmsBuilder::class, 'id', 'sms_template_id');
    }

    public function campaign_emails() {
        return $this->hasMany(CampaignEmail::class, 'campaign_id', 'id');
    }

    public function relationWithSmtpServer() {
        return $this->hasOne(EmailService::class, 'id', 'smtp_server_id');
    }

    public function emailService() {
        return $this->hasOne(EmailService::class, 'id', 'smtp_server_id');
    }

    public function relationWithSMSServer() {
        return $this->hasOne(SmsService::class, 'id', 'sms_server_id');
    }

    public function campaign_attachment() {
        return $this->hasOne(CampaignAttachment::class, 'campaign_id', 'id');
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

    public function senderEmails() {
        return $this->hasManyThrough(SenderEmailId::class, EmailService::class, 'id', 'email_service_id', 'smtp_server_id', 'id');
    }
}
