<?php

namespace App\Search\Dto\Response\Common;
use App\Search\Trait\GetNameTrait;
use Symfony\Component\Validator\Constraints as Assert;

readonly class GameAppearanceDto
{
    use GetNameTrait;

    public function __construct(
        #[Assert\NotNull]
        private string $name
    ) {}

    public function getName(): string
    {
        return $this->name;
    }
}
