<?php

namespace App\Search\Dto\Response\Item;
use Symfony\Component\Validator\Constraints as Assert;

readonly class ItemSpritesDto
{
    public function __construct(
        #[Assert\NotNull]
        private string $default
    ){}

    public function getDefault(): string
    {
        return $this->default;
    }
}
