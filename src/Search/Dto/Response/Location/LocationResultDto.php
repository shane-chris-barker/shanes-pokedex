<?php

namespace App\Search\Dto\Response\Location;

use App\Search\Dto\Interface\SearchResultDtoInterface;
use App\Search\Trait\GetNameTrait;
use Symfony\Component\Validator\Constraints as Assert;

class LocationResultDto implements SearchResultDtoInterface
{
    use GetNameTrait;

    public function __construct(
        #[Assert\NotNull]
        private readonly int $id,
        #[Assert\NotNull]
        private readonly string $name,
        #[Assert\NotNull]
        private readonly string $region,
        #[Assert\All([new Assert\Type(LocationAreaDto::class)])]
        private array $areas = []
    ){}

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAreas(): array
    {
        return $this->areas;
    }

    public function addArea(LocationAreaDto $locationAreaDto): void
    {
        $this->areas[] = $locationAreaDto;
    }

    public function getRegion(): string
    {
        return $this->region;
    }
}
