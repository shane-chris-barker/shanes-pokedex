<?php

namespace App\Search\Handler;

use App\Search\Dto\Interface\SearchResultDtoInterface;
use App\Search\Dto\Request\SearchRequestDto;
use App\Search\Exception\PokeApiSearchException;
use App\Search\Exception\SearchTypeNotFoundException;

interface SearchHandlerInterface
{
    /**
     * @param string $searchType
     * @return bool
     */
    public function supports(string $searchType): bool;

    /**
     * @param SearchRequestDto $dto
     * @throws PokeApiSearchException
     * @return SearchResultDtoInterface
     */
    public function search(SearchRequestDto $dto): SearchResultDtoInterface;
}

