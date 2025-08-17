<?php

namespace App\Tests\Search\Mapper;

use App\Search\Dto\Interface\SearchResultDtoInterface;
use App\Search\Dto\Response\Item\ItemResultDto;
use App\Search\Dto\Response\Item\ItemSpritesDto;
use App\Search\Dto\Response\SearchResponseDto;
use App\Search\Mapper\ItemResultMapper;
use PHPUnit\Framework\TestCase;

class ItemResultMapperTest extends TestCase
{
    private SearchResponseDto $searchResponseDto;
    private ItemResultMapper $sut;

    public function setUp(): void
    {
        $this->searchResponseDto = new SearchResponseDto(
            [
                'id' => 1,
                'sprites' => [
                    'default' => 'DEFAULT_SPRITE',
                ],
                'flavor_text_entries' => [
                    0 => [
                        'text' => 'DESCRIPTION',
                    ]
                ],
                'category' => [
                    'name' => 'CATEGORY',
                ]
            ],
            'Search Term'
        );

        $this->sut = new ItemResultMapper();
    }

    public function testMapper(): void
    {
        $resultDto = $this->sut->map($this->searchResponseDto);
        $this->assertInstanceOf(SearchResultDtoInterface::class, $resultDto);
        $this->assertInstanceOf(ItemResultDto::class, $resultDto);

        $sprite = $resultDto->getSprites();
        $this->assertEquals(1, $resultDto->getId());
        $this->assertEquals($this->searchResponseDto->getSearchTerm(), $resultDto->getName());
        $this->assertInstanceOf(ItemSpritesDto::class, $resultDto->getSprites());
        $this->assertEquals('DEFAULT_SPRITE', $sprite->getDefault());
        $this->assertEquals('DESCRIPTION', $resultDto->getDescription());
        $this->assertEquals('CATEGORY', $resultDto->getCategory());
    }
}
