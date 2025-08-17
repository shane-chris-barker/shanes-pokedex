<?php

namespace App\Search\Handler;

use App\Enum\SearchType;
use App\Search\Client\SearchClientInterface;
use App\Search\Dto\Interface\SearchResultDtoInterface;
use App\Search\Dto\Request\SearchRequestDto;
use App\Search\Mapper\ResultMapperInterface;

class LocationSearchHandler implements SearchHandlerInterface
{
    private SearchClientInterface $client;
    private ResultMapperInterface $resultMapper;

    public function __construct(
        SearchClientInterface $client,
        ResultMapperInterface $resultMapper
    ) {
        $this->client = $client;
        $this->resultMapper = $resultMapper;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(string $searchType): bool
    {
        return $searchType === SearchType::Location->value;
    }

    /**
     * {@inheritDoc}
     */
    public function search(SearchRequestDto $dto): SearchResultDtoInterface
    {
        return $this->resultMapper->map(
            $this->client->search($dto->getSlugName())
        );
    }
}
