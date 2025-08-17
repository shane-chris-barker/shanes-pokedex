<?php

namespace App\Tests\Search\Handler;

use App\Enum\SearchType;
use App\Search\Client\SearchClientInterface;
use App\Search\Dto\Interface\SearchResultDtoInterface;
use App\Search\Dto\Request\SearchRequestDto;
use App\Search\Dto\Response\SearchResponseDto;
use App\Search\Exception\PokeApiSearchException;
use App\Search\Handler\PokemonSearchHandler;
use App\Search\Mapper\ResultMapperInterface;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PokemonSearchHandlerTest extends TestCase
{
    private SearchClientInterface|MockObject $searchClientInterfaceMock;
    private SearchClientInterface|MockObject $speciesSearchClientInterfaceMock;
    private ResultMapperInterface|MockObject $resultMapperInterfaceMock;
    private PokemonSearchHandler $handler;

    /**
     * @return void
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->searchClientInterfaceMock = $this->createMock(SearchClientInterface::class);
        $this->speciesSearchClientInterfaceMock = $this->createMock(SearchClientInterface::class);
        $this->resultMapperInterfaceMock = $this->createMock(ResultMapperInterface::class);
        $this->handler = new PokemonSearchHandler(
            $this->searchClientInterfaceMock,
            $this->speciesSearchClientInterfaceMock,
            $this->resultMapperInterfaceMock
        );
    }

    public function testSupportsReturnsTrueForPokemonSearchType(): void
    {
        $this->assertTrue(
            $this->handler->supports(
                SearchType::Pokemon->value
            )
        );
    }

    public function testSupportsReturnsFalseForNonePokemonSearchType(): void
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
    public function testSearchMergesData(): void
    {
        $pokemonName = 'pikachu';
        $dto = $this->createMock(SearchRequestDto::class);
        $dto->method('getSlugName')->willReturn($pokemonName);

        $pokemonData = ['id' => 25, 'name' => $pokemonName];
        $speciesData = ['name' => $pokemonName];

        $pokemonDto = new SearchResponseDto($pokemonData, $pokemonName);
        $speciesDto = new SearchResponseDto($speciesData, $pokemonName);

        $this
            ->searchClientInterfaceMock
            ->expects($this->once())
            ->method('search')
            ->with($pokemonName)
            ->willReturn($pokemonDto);

        $this
            ->speciesSearchClientInterfaceMock
            ->expects($this->once())
            ->method('search')
            ->with($pokemonName)
            ->willReturn($speciesDto);

        $resultDto = $this->createMock(SearchResultDtoInterface::class);

        $this
            ->resultMapperInterfaceMock
            ->expects($this->once())
            ->method('map')
            ->with($this->callback(function (SearchResponseDto $dto) use ($pokemonData, $speciesData) {
                $merged = $dto->getResponseData();
                return $merged['id'] === 25
                    && $merged['name'] === $pokemonData['name']
                    && $merged['species'] === $speciesData;
            }))->willReturn($resultDto);

        $result = $this->handler->search($dto);
        $this->assertSame($resultDto, $result);

    }

}
