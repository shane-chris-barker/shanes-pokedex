<?php

namespace App\Search\Client;

use App\Search\Dto\Response\SearchResponseDto;
use App\Search\Exception\PokeApiSearchException;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;

class LocationSearchClient implements SearchClientInterface
{
    private ClientInterface $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritDoc}
     */
    public function search(string $searchTerm): SearchResponseDto
    {
        try {
            $response = $this->client->request(
                'GET',
                "location/{$searchTerm}"
            );
        } catch (GuzzleException $e) {
            throw new PokeApiSearchException(
                $e->getMessage()
            );
        }

        return new SearchResponseDto(
            json_decode($response->getBody()->getContents(), true),
            $searchTerm
        );
    }
}
