<?php

namespace App\Service;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class DataFetcherService
{
    public function __construct(
        private HttpClientInterface $client,
    ) {
        $this->client = $client;
    }

    public function fetchData(): array
    {
        $content = [];

        for ($i = 0; $i < 20; $i++) { 
            $response = $this->client->request(
                'GET',
                'https://api.spoonacular.com/recipes/random',
                [
                    'query' => [
                        'apiKey' => 'c560e416d8fb4269b0235ddc8a262fbd',
                        'number' => 1
                    ],
                ]
            );

            $content[] = $response->toArray();
        }

        return $content;
    }
}