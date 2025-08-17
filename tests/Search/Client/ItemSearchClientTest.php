<?php

namespace App\Tests\Search\Client;

use App\Search\Client\ItemSearchClient;
use App\Search\Dto\Response\SearchResponseDto;
use App\Search\Exception\PokeApiSearchException;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Psr7\Request;

class ItemSearchClientTest extends TestCase
{
    private ClientInterface|MockObject $httpClient;
    private ItemSearchClient $itemSearchClient;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->httpClient = $this->createMock(ClientInterface::class);
        $this->itemSearchClient = new ItemSearchClient($this->httpClient);
    }

    /**
     * @return void
     * @throws PokeApiSearchException
     */
    public function testSearchReturnsDtoOnSuccess(): void
    {
        $searchTerm = 'potion';

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
            ->with("GET", "item/{$searchTerm}")
            ->willReturn($mockResponse);

        $result = $this->itemSearchClient->search($searchTerm);

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
                    new Request("GET", "item/{$searchTerm}")
                )
            );

        $this->expectException(PokeApiSearchException::class);
        $this->expectExceptionMessage("Not Found");
        $this->itemSearchClient->search($searchTerm);
    }
}
