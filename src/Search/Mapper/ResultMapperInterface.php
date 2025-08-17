<?php

namespace App\Search\Mapper;

use App\Search\Dto\Interface\SearchResultDtoInterface;
use App\Search\Dto\Response\SearchResponseDto;

interface ResultMapperInterface
{
    /**
     * @param SearchResponseDto $dto
     * @return SearchResultDtoInterface
     */
    public function map(SearchResponseDto $dto): SearchResultDtoInterface;
}
