<?php

namespace App\Search\Dto\Request;

use App\Search\Trait\GetNameTrait;
use Symfony\Component\Validator\Constraints as Assert;


readonly class SearchRequestDto
{
    use GetNameTrait;

    public function __construct(
        #[Assert\NotBlank]
        private string $name,
        #[Assert\NotNull]
        private string $searchType)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSearchType(): string
    {
        return $this->searchType;
    }
}
