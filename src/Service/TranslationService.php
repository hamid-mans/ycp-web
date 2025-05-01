<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class TranslationService
{
    private HttpClientInterface $client;
    private string $apiKey;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
        $this->apiKey = $_ENV['DEEPL_API_KEY'];
    }

    public function translateToFrench(string $text): ?string
    {
        $response = $this->client->request('POST', 'https://api-free.deepl.com/v2/translate', [
            'body' => [
                'auth_key' => $this->apiKey,
                'text' => $text,
                'target_lang' => 'FR'
            ]
        ]);

        $data = $response->toArray();

        return $data['translations'][0]['text'] ?? null;
    }
}