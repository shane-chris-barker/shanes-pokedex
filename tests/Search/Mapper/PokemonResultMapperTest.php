<?php

namespace App\Tests\Search\Mapper;

use App\Search\Dto\Interface\SearchResultDtoInterface;
use App\Search\Dto\Response\Common\GameAppearanceDto;
use App\Search\Dto\Response\Pokemon\PokemonCryDto;
use App\Search\Dto\Response\Pokemon\PokemonResultDto;
use App\Search\Dto\Response\Pokemon\PokemonSpritesDto;
use App\Search\Dto\Response\Pokemon\PokemonTypeDto;
use App\Search\Dto\Response\SearchResponseDto;
use App\Search\Mapper\PokemonResultMapper;
use PHPUnit\Framework\TestCase;

class PokemonResultMapperTest extends TestCase
{
    private SearchResponseDto $searchResponseDto;
    private PokemonResultMapper $sut;

    public function setUp(): void
    {
        $this->searchResponseDto = new SearchResponseDto(
            [
                'id' => 1,
                'species' => [
                    'flavor_text_entries' => [
                        0 => [
                            'flavor_text' => 'description'
                        ]
                    ]
                ],
                'sprites' => [
                    'back_default' => 'back_default',
                    'back_female' => 'back_female',
                    'back_shiny' => 'back_shiny',
                    'back_shiny_female' => 'back_shiny_female',
                    'front_default' => 'front_default',
                    'front_female' => 'front_female',
                    'front_shiny' => 'front_shiny',
                    'front_shiny_female' => 'front_shiny_female'
                ],
                'types' => [
                    0 => [
                        'type' => [
                            'name' => 'type_1'
                        ]
                    ],
                    1 => [
                        'type' => [
                            'name' => 'type_2'
                        ]
                    ]
                ],
                'cries' => [
                    'sound_1_name' => 'sound_1_url',
                    'sound_2_name' => 'sound_2_url',
                ],
                'game_indices' => [
                    0 => [
                        'version' => [
                            'name' => 'game_1'
                        ]
                    ],
                    1 => [
                        'version' => [
                            'name' => 'game_2'
                        ]
                    ]
                ]
            ],
            'Search Term'
        );

        $this->sut = new PokemonResultMapper();
    }

    public function testMapper(): void
    {
        $resultDto = $this->sut->map($this->searchResponseDto);
        $this->assertInstanceOf(SearchResultDtoInterface::class, $resultDto);
        $this->assertInstanceOf(PokemonResultDto::class, $resultDto);
        $this->assertInstanceOf(PokemonSpritesDto::class, $resultDto->getSprites());

        $types = $resultDto->getTypes();
        $games = $resultDto->getGameAppearances();
        $cries = $resultDto->getPokemonSounds();

        $this->assertInstanceOf(PokemonTypeDto::class, $types[0]);
        $this->assertInstanceOf(GameAppearanceDto::class, $games[0]);
        $this->assertInstanceOf(PokemonCryDto::class, $cries[0]);

        $this->assertEquals(1, $resultDto->getId());
        $this->assertEquals('back_default', $resultDto->getSprites()->getBackDefault());
        $this->assertEquals('back_female', $resultDto->getSprites()->getBackFemale());
        $this->assertEquals('back_shiny', $resultDto->getSprites()->getBackShiny());
        $this->assertEquals('back_shiny_female', $resultDto->getSprites()->getBackShinyFemale());
        $this->assertEquals('front_female', $resultDto->getSprites()->getFrontFemale());
        $this->assertEquals('front_shiny', $resultDto->getSprites()->getFrontShiny());
        $this->assertEquals('front_shiny_female', $resultDto->getSprites()->getFrontShinyFemale());

        $this->assertEquals('game_1', $games[0]->getName());
        $this->assertEquals('game_2', $games[1]->getName());

        $this->assertEquals('type_1', $types[0]->getName());
        $this->assertEquals('type_2', $types[1]->getName());

        $this->assertEquals('sound_1_name', $cries[0]->getSoundName());
        $this->assertEquals('sound_2_name', $cries[1]->getSoundName());
        $this->assertEquals('sound_1_url', $cries[0]->getUrl());
        $this->assertEquals('sound_2_url', $cries[1]->getUrl());

        $this->assertEquals('description', $resultDto->getDescription());
        $this->assertEquals('Search Term', $resultDto->getName());
    }
}
