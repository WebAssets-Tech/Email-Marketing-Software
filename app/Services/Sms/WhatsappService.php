<?php

namespace App\Services\Sms;

use App\Models\SmsService;
use Infobip\Exceptions\InfobipValidationException;

class WhatsappService {
    public function __construct(public SmsService $whatsapp) {
    }

    /**
     * Send Text Message to whatsapp using infobip's api
     *
     * @return array
     *
     * @throws InfobipValidationException
     */
    public function sendText(string $message, string $to, ?string $from = null, ?SmsService $whatsapp = null) {
        if ($whatsapp == null) {
            $whatsapp = $this->whatsapp;
        }
        $infobipClient = new \Infobip\InfobipClient(
            $whatsapp->sms_token,
            $whatsapp->url,
            5
        );
        $resource = new \Infobip\Resources\WhatsApp\WhatsAppTextMessageResource(
            $from ?? $whatsapp?->sender?->sms_from,
            $to,
            new \Infobip\Resources\WhatsApp\Models\TextContent($message)
        );

        $response = $infobipClient
            ->whatsApp()
            ->sendWhatsAppTextMessage($resource);

        return $response;
    }

    /** Sends text message to whats app to organization number
     * @return array
     *
     * @throws InfobipValidationException
     */
    public function sendTestMessage() {
        return $this->sendText('Test Message From '.config('app.name'), org('test_connection_sms'));
    }
}
