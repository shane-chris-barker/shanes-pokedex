<?php

namespace App\Tests\Search\Handler;

use App\Enum\SearchType;
use App\Search\Client\SearchClientInterface;
use App\Search\Dto\Interface\SearchResultDtoInterface;
use App\Search\Dto\Request\SearchRequestDto;
use App\Search\Dto\Response\SearchResponseDto;
use App\Search\Exception\PokeApiSearchException;
use App\Search\Handler\ItemSearchHandler;
use App\Search\Mapper\ResultMapperInterface;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ItemSearchHandlerTest extends TestCase
{
    private SearchClientInterface|MockObject $searchClientInterfaceMock;
    private ResultMapperInterface|MockObject $resultMapperInterfaceMock;
    private ItemSearchHandler $handler;

    /**
     * @return void
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->searchClientInterfaceMock = $this->createMock(SearchClientInterface::class);
        $this->resultMapperInterfaceMock = $this->createMock(ResultMapperInterface::class);
        $this->handler = new ItemSearchHandler(
            $this->searchClientInterfaceMock,
            $this->resultMapperInterfaceMock
        );
    }

    public function testSupportsReturnsTrueForItemSearchType(): void
    {
        $this->assertTrue(
            $this->handler->supports(
                SearchType::Item->value
            )
        );
    }

    public function testSupportsReturnsFalseForNoneItemSearchType(): void
    {
        $this->assertFalse(
            $this->handler->supports(
                SearchType::Pokemon->value
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
            ->willReturn('potion');

        $responseDto = new SearchResponseDto(
            [
                'id' => 1,
                'name' => 'potion'
            ],
            'potion'
        );

        $resultDto = $this->createMock(SearchResultDtoInterface::class);

        $this
            ->searchClientInterfaceMock
            ->expects($this->once())
            ->method('search')
            ->with('potion')
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
