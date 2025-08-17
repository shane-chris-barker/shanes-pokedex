<?php

namespace App\Search\Dto\Response;

use Symfony\Component\Validator\Constraints as Assert;

class SearchResponseDto
{
    public function __construct(
        #[Assert\NotBlank]
        private array $responseData,
        #[Assert\NotNull]
        private readonly string $searchTerm)
    {
    }

    public function getResponseData(): array
    {
        return $this->responseData;
    }

    public function getSearchTerm(): string
    {
        return $this->searchTerm;
    }

    public function SetResponseData(array $responseData): void
    {
        $this->responseData = $responseData;
    }
}
