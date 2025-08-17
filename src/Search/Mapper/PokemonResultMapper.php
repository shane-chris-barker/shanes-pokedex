<?php

namespace App\Search\Mapper;

use App\Search\Dto\Interface\SearchResultDtoInterface;
use App\Search\Dto\Response\Common\GameAppearanceDto;
use App\Search\Dto\Response\Pokemon\PokemonCryDto;
use App\Search\Dto\Response\Pokemon\PokemonResultDto;
use App\Search\Dto\Response\Pokemon\PokemonSpritesDto;
use App\Search\Dto\Response\Pokemon\PokemonTypeDto;
use App\Search\Dto\Response\SearchResponseDto;

class PokemonResultMapper implements ResultMapperInterface
{

    /**
     * {@inheritDoc}
     */
    public function map(SearchResponseDto $dto): SearchResultDtoInterface
    {
        $data = $dto->getResponseData();
        $dto = new PokemonResultDto(
            $data['id'],
            $dto->getSearchTerm(),
            new PokemonSpritesDto(
                $data['sprites']['back_default'],
                $data['sprites']['back_female'],
                $data['sprites']['back_shiny'],
                $data['sprites']['back_shiny_female'],
                $data['sprites']['front_default'],
                $data['sprites']['front_female'],
                $data['sprites']['front_shiny'],
                $data['sprites']['front_shiny_female'],
            ),
            $data['species']['flavor_text_entries'][0]['flavor_text']
        );

        foreach ($data['types'] as $type) {
            $dto->addType(new PokemonTypeDto($type['type']['name']));
        }

        foreach ($data['game_indices'] as $game) {
            $dto->addGameAppearance(new GameAppearanceDto($game['version']['name']));
        }

        foreach ($data['cries'] as $key => $value) {
            $dto->addPokemonSound(new PokemonCryDto(
                $key,
                $value,
            ));
        }

        return $dto;
    }
}
