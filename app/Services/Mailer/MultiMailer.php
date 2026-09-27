<?php

declare(strict_types=1);

namespace App\Services\Mailer;

// use Illuminate\Mail\Mailer;
use App\Models\EmailService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;

class MultiMailer {
    /**
     * Send Mails using different providers
     * --if array is used as provider make sure
     * to keep the structure from mail config
     *
     * @return \Illuminate\Mail\Mailer
     */
    public static function mail(EmailService|array $provider, ?string $name = 'dynamic') {
        $config = self::setMailerConfig($provider, $name);

        return Mail::mailer($name);
    }

    /**
     * Set Configuration options for a mail provider
     *
     * @return \Illuminate\Config\Repository|mixed
     */
    public static function setMailerConfig(EmailService|array $provider, ?string $name = 'dynamic') {
        Artisan::call('config:clear');
        $provider = is_array($provider) ? (object) $provider : $provider;
        config([
            "mail.mailers.$name" => [
                'transport' => $provider->driver,
                'host' => $provider->host,
                'port' => $provider->port,
                'encryption' => $provider->encryption,
                'username' => $provider->username,
                'password' => $provider->password,
                'from_name' => $provider->sender_email?->sender_name,
                'from_email' => $provider->sender_email?->sender_email_address,
                'timeout' => null,
                'auth_mode' => null,
            ],
        ]);

        return config("mail.mailers.$name");
    }
}
