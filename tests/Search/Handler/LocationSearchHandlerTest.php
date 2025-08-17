<?php

namespace App\Tests\Search\Handler;

use App\Enum\SearchType;
use App\Search\Client\SearchClientInterface;
use App\Search\Dto\Interface\SearchResultDtoInterface;
use App\Search\Dto\Request\SearchRequestDto;
use App\Search\Dto\Response\SearchResponseDto;
use App\Search\Exception\PokeApiSearchException;
use App\Search\Handler\LocationSearchHandler;
use App\Search\Mapper\ResultMapperInterface;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class LocationSearchHandlerTest extends TestCase
{
    private SearchClientInterface|MockObject $searchClientInterfaceMock;
    private ResultMapperInterface|MockObject $resultMapperInterfaceMock;
    private LocationSearchHandler $handler;

    /**
     * @return void
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->searchClientInterfaceMock = $this->createMock(SearchClientInterface::class);
        $this->resultMapperInterfaceMock = $this->createMock(ResultMapperInterface::class);
        $this->handler = new LocationSearchHandler(
            $this->searchClientInterfaceMock,
            $this->resultMapperInterfaceMock
        );
    }
    public function testSupportsReturnsTrueForLocationSearchType(): void
    {
        $this->assertTrue(
            $this->handler->supports(
                SearchType::Location->value
            )
        );
    }

    public function testSupportsReturnsFalseForNoneLocationSearchType(): void
    {
        $this->assertFalse(
            $this->handler->supports(
                SearchType::Item->value
            )
        );
    }

    /**
     * @return void
     * @throws Exception
     * @throws PokeApiSearchException
     */
    public function testSearchDelegatesToClientAndMapper(): void
    {
        $dto = $this->createMock(SearchRequestDto::class);
        $dto
            ->method('getSlugName')
            ->willReturn('pallet-town');

        $responseDto = new SearchResponseDto(
            [
                'id' => 1,
                'name' => 'pallet-town'
            ],
            'pallet-town'
        );

        $resultDto = $this->createMock(SearchResultDtoInterface::class);

        $this
            ->searchClientInterfaceMock
            ->expects($this->once())
            ->method('search')
            ->with('pallet-town')
            ->willReturn($responseDto);

        $this
            ->resultMapperInterfaceMock
            ->expects($this->once())
            ->method('map')
            ->with($responseDto)
            ->willReturn($resultDto);

        $result = $this->handler->search($dto);
        $this->assertEquals($resultDto, $result);
    }
}
