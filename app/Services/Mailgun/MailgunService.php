<?php

namespace App\Services\Mailgun;

use Illuminate\Support\Facades\Http;

class MailgunService {
    public string $apiKey = '';

    public function __construct(string $apiKey) {
        $this->apiKey = $apiKey;
    }

    public function domains(): mixed {
        $response = Http::mailgun($this->apiKey)
            ->get('/domains');

        $result = $response->object();

        return $result;
    }

    public function getDomain(string $name): mixed {
        $response = Http::mailgun($this->apiKey)
            ->get("/domains/{$name}");

        $result = $response->object();

        return $result;
    }

    public function createDomain(string $name): mixed {
        $response = Http::mailgun($this->apiKey)
            ->withQueryParameters([
                'name' => $name,
            ])
            ->post('/domains');

        $result = $response->object();

        return $result;
    }

    public function verifyDomain(string $name) {
        $response = Http::mailgun($this->apiKey)
            ->put("/domains/{$name}/verify");

        $result = $response->object();

        return $result;

    }

    public function deleteDomain(string $name) {
        $response = Http::mailgun($this->apiKey)
            ->delete("/domains/{$name}");

        $result = $response->object();

        return $result;

    }
}
