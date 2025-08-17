<?php

namespace App\Search\Mapper;

use App\Search\Dto\Interface\SearchResultDtoInterface;
use App\Search\Dto\Response\Item\ItemResultDto;
use App\Search\Dto\Response\Item\ItemSpritesDto;
use App\Search\Dto\Response\SearchResponseDto;

class ItemResultMapper implements ResultMapperInterface
{
    /**
     * {@inheritDoc}
     */
    public function map(SearchResponseDto $dto): SearchResultDtoInterface
    {
        $data = $dto->getResponseData();
        return new ItemResultDto(
            $data['id'],
            $dto->getSearchTerm(),
            new ItemSpritesDto(
                $data['sprites']['default']
            ),
            $data['flavor_text_entries'][0]['text'],
            $data['category']['name']
        );
    }
}
