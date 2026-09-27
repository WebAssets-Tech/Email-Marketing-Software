<?php

namespace App\Providers;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class MacroServiceProvider extends ServiceProvider {
    /**
     * Register services.
     */
    public function register(): void {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void {
        // Macro for mailgun api
        Http::macro('mailgun', function (string $apiKey, string $version = 'v4') {
            return Http::withBasicAuth('api', $apiKey)
                ->baseUrl("https://api.mailgun.net/{$version}/");
        });

        /**
         * HTTP Gpt macro for chat gpt api
         */
        Http::macro('gpt', function (?string $prompt = null, ?string $apiKey = null) {
            $apiKey = $apiKey ?? env('CHATGPT_API_KEY');
            $http = Http::withoutVerifying()
                ->withHeaders([
                    'Authorization' => 'Bearer '.$apiKey,
                    'Content-Type' => 'application/json',
                ])->baseUrl('https://api.openai.com/v1/');
            if ($prompt) {
                $http = $http->post('/chat/completions', [
                    'model' => 'gpt-3.5-turbo-1106',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'you are a helpful assistant!',
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt,
                        ],

                    ],
                    'max_tokens' => 1000,
                    'temperature' => 0.5,
                ]);
            }
            if (isset($http->json()['error'])) {
                throw new Exception($http->json()['error']['message'], 1);
            }

            return $http;
        });
    }
}
