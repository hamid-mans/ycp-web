<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class QuoteService
{
    private HttpClientInterface $client;
    private CacheInterface $cache;
    private string $apiKey;

    public function __construct(HttpClientInterface $client, CacheInterface $cache)
    {
        $this->client = $client;
        $this->cache = $cache;
        $this->apiKey = $_ENV['NINJAS_API_KEY'];
    }

    public function getQuote(): array
    {
        return $this->cache->get('quote_of_the_day_' . date('Y-m-d'), function (ItemInterface $item) {
            $item->expiresAt(new \DateTime('tomorrow midnight'));

            $response = $this->client->request('GET', 'https://api.api-ninjas.com/v1/quotes', [
                'headers' => [
                    'X-Api-Key' => $this->apiKey
                ]
            ]);

            $quotes = $response->toArray();
            $quote = $quotes[array_rand($quotes)];

            return $quote;
        });
    }
}
