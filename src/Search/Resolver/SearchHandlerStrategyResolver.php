<?php

namespace App\Search\Resolver;

use App\Search\Exception\SearchTypeNotFoundException;
use App\Search\Handler\SearchHandlerInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

class SearchHandlerStrategyResolver
{
    private iterable $strategies;

    public function __construct(
        #[AutowireIterator('search.strategy')]
        iterable $strategies
    ) {
        $this->strategies = $strategies;
    }

    /**
     * @throws SearchTypeNotFoundException
     */
    public function resolve(string $searchType): SearchHandlerInterface
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($searchType)) {
                return $strategy;
            }
        }
        throw new SearchTypeNotFoundException();
    }
}
