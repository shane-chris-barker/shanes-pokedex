<?php

namespace App\Search\Mapper;

use App\Search\Dto\Interface\SearchResultDtoInterface;
use App\Search\Dto\Response\Location\LocationAreaDto;
use App\Search\Dto\Response\Location\LocationResultDto;
use App\Search\Dto\Response\SearchResponseDto;

class LocationResultMapper implements ResultMapperInterface
{
    /**
     * {@inheritDoc}
     */
    public function map(SearchResponseDto $dto): SearchResultDtoInterface
    {
        $data = $dto->getResponseData();
        $location = new LocationResultDto(
            $data['id'],
            $dto->getSearchTerm(),
            $data['region']['name']
        );

        foreach ($data['areas'] as $area) {
            $location->addArea(
                new LocationAreaDto($area['name'])
            );
        }

        return $location;
    }
}
