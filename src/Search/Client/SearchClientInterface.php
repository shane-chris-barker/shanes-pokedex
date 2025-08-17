<?php

namespace App\Search\Client;

use App\Search\Dto\Response\SearchResponseDto;
use App\Search\Exception\PokeApiSearchException;

interface SearchClientInterface
{
    /**
     * @param string $searchTerm
     * @return SearchResponseDto
     * @throws PokeApiSearchException
     */
    public function search(string $searchTerm): SearchResponseDto;
}
