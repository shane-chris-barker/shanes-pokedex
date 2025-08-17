<?php

namespace App\Tests\Search\Mapper;

use App\Search\Dto\Interface\SearchResultDtoInterface;
use App\Search\Dto\Response\Location\LocationAreaDto;
use App\Search\Dto\Response\Location\LocationResultDto;
use App\Search\Dto\Response\SearchResponseDto;
use App\Search\Mapper\LocationResultMapper;
use PHPUnit\Framework\TestCase;

class LocationResultMapperTest extends TestCase
{
    private SearchResponseDto $searchResponseDto;
    private LocationResultMapper $sut;

    public function setUp(): void
    {
        $this->searchResponseDto = new SearchResponseDto(
            [
                'id' => 1,
                'region' => [
                    'name' => 'REGION_NAME'
                ],
                'areas' => [
                    0 => [
                        'name' => 'AREA_1'
                    ],
                    1 => [
                        'name' => 'AREA_2'
                    ]
                ]
            ],
            'Search Term'
        );

        $this->sut = new LocationResultMapper();
    }

    public function testMapper(): void
    {
        $resultDto = $this->sut->map($this->searchResponseDto);
        $this->assertInstanceOf(SearchResultDtoInterface::class, $resultDto);
        $this->assertInstanceOf(LocationResultDto::class, $resultDto);
        $areas = $resultDto->getAreas();

        $this->assertEquals(1, $resultDto->getId());
        $this->assertEquals($this->searchResponseDto->getSearchTerm(), $resultDto->getName());
        $this->assertInstanceOf(LocationAreaDto::class, $areas[0]);
        $this->assertEquals('AREA_1', $areas[0]->getName());
        $this->assertEquals('AREA_2', $areas[1]->getName());
        $this->assertEquals('REGION_NAME', $resultDto->getRegion());
    }
}
