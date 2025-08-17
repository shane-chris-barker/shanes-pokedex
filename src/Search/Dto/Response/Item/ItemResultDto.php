<?php

namespace App\Search\Dto\Response\Item;
use App\Search\Dto\Interface\SearchResultDtoInterface;
use App\Search\Trait\GetNameTrait;
use Symfony\Component\Validator\Constraints as Assert;

readonly class ItemResultDto implements SearchResultDtoInterface
{
    use GetNameTrait;

    public function __construct(
        #[Assert\NotNull]
        private int $id,
        #[Assert\NotNull]
        private string $name,
        private ItemSpritesDto $sprites,
        #[Assert\NotNull]
        private string $description,
        #[Assert\NotNull]
        private string $category
    ){}

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSprites(): ItemSpritesDto
    {
        return $this->sprites;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getCategory(): string
    {
        return $this->category;
    }
}
