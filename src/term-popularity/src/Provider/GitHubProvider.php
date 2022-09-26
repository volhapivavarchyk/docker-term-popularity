<?php

declare(strict_types=1);

namespace App\Provider;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\HttpFoundation\Response;

class GitHubProvider implements ProviderInterface
{
    private const PROVIDER_NAME = 'github';

    public const API_URL        = 'https://api.github.com/search/issues';

    public Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function getName(): string
    {
        return self::PROVIDER_NAME;
    }

    /**
     * @throws GuzzleException
     */
    public function getList(string $query): array
    {
        $response = $this->client->request(
            'GET',
            self::API_URL,
            [
                'query' => [
                    'q'     => $query,
                    'sort'  => 'created',
                    'order' => 'asc',
                ],
            ]
        );

        $status = $response->getStatusCode();

        return $status === Response::HTTP_OK
            ? json_decode($response->getBody()->getContents(), true)
            : [];
    }
}