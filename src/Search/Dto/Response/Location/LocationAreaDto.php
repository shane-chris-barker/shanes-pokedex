<?php

namespace App\Search\Dto\Response\Location;

use Symfony\Component\Validator\Constraints as Assert;
use App\Search\Trait\GetNameTrait;

readonly class LocationAreaDto
{
    use GetNameTrait;

    public function __construct(
        #[Assert\NotNull]
        private string $name
    ){}

    public function getName(): string
    {
        return $this->name;
    }
}
