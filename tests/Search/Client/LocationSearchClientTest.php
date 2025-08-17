<?php

namespace App\Tests\Search\Client;

use App\Search\Client\LocationSearchClient;
use App\Search\Dto\Response\SearchResponseDto;
use App\Search\Exception\PokeApiSearchException;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class LocationSearchClientTest extends TestCase
{
    private ClientInterface|MockObject $httpClient;
    private LocationSearchClient $locationSearchClient;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->httpClient = $this->createMock(ClientInterface::class);
        $this->locationSearchClient = new LocationSearchClient($this->httpClient);
    }

    /**
     * @return void
     * @throws PokeApiSearchException
     */
    public function testSearchReturnsDtoOnSuccess(): void
    {
        $searchTerm = 'pallet-town';

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
            ->with("GET", "location/{$searchTerm}")
            ->willReturn($mockResponse);

        $result = $this->locationSearchClient->search($searchTerm);

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
                    new Request("GET", "location/{$searchTerm}")
                )
            );

        $this->expectException(PokeApiSearchException::class);
        $this->expectExceptionMessage("Not Found");
        $this->locationSearchClient->search($searchTerm);
    }
}
