<?php

namespace App\Search\Handler;

use App\Enum\SearchType;
use App\Search\Client\SearchClientInterface;
use App\Search\Dto\Interface\SearchResultDtoInterface;
use App\Search\Dto\Request\SearchRequestDto;
use App\Search\Mapper\ResultMapperInterface;

class PokemonSearchHandler implements SearchHandlerInterface
{
    private SearchClientInterface $client;
    private SearchClientInterface $speciesSearchClient;
    private ResultMapperInterface $resultMapper;

    public function __construct(
        SearchClientInterface $client,
        SearchClientInterface $speciesSearchClient,
        ResultMapperInterface $resultMapper
    ) {
        $this->client = $client;
        $this->speciesSearchClient = $speciesSearchClient;
        $this->resultMapper = $resultMapper;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(string $searchType): bool
    {
        return $searchType === SearchType::Pokemon->value;
    }

    /**
     * {@inheritDoc}
     */
    public function search(SearchRequestDto $dto): SearchResultDtoInterface
    {
        $pokemonResponseDto = $this->client->search($dto->getSlugName());
        $speciesResponseDto = $this->speciesSearchClient->search($dto->getSlugName());
        $data = $pokemonResponseDto->getResponseData();
        $data['species'] = $speciesResponseDto->getResponseData();
        $pokemonResponseDto->setResponseData($data);
        return $this->resultMapper->map($pokemonResponseDto);
    }
}
