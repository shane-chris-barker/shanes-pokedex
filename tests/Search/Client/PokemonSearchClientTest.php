<?php

namespace App\Tests\Search\Client;

use PHPUnit\Framework\TestCase;
use App\Search\Client\PokemonSearchClient;
use App\Search\Dto\Response\SearchResponseDto;
use App\Search\Exception\PokeApiSearchException;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use GuzzleHttp\Psr7\Request;

class PokemonSearchClientTest extends TestCase
{
    private ClientInterface|MockObject $httpClient;
    private PokemonSearchClient $pokemonSearchClient;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->httpClient = $this->createMock(ClientInterface::class);
        $this->pokemonSearchClient = new PokemonSearchClient($this->httpClient);
    }

    /**
     * @return void
     * @throws PokeApiSearchException
     */
    public function testSearchReturnsDtoOnSuccess(): void
    {
        $searchTerm = 'pikachu';

        $expectedData = [
            'id' => 1,
            'name' => $searchTerm
        ];

        $mockResponse = new Response(
            200,
            [],
            json_encode($expectedData)
        );

        $this
            ->httpClient
            ->expects($this->once())
            ->method('request')
            ->with("GET", "pokemon/{$searchTerm}")
            ->willReturn($mockResponse);

        $result = $this->pokemonSearchClient->search($searchTerm);

        $this->assertInstanceOf(SearchResponseDto::class, $result);
        $this->assertSame($expectedData, $result->getResponseData());
        $this->assertSame($searchTerm, $result->getSearchTerm());
    }

    public function testSearchThrowsPokeApiSearchException(): void
    {
        $searchTerm = 'invalid';

        $this
            ->httpClient
            ->method('request')
            ->willThrowException(
                new RequestException(
                    "Not Found",
                    new Request("GET", "pokemon/{$searchTerm}")
                )
            );

        $this->expectException(PokeApiSearchException::class);
        $this->expectExceptionMessage("Not Found");
        $this->pokemonSearchClient->search($searchTerm);
    }
}
