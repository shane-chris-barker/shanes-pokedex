<?php

namespace App\Search\Dto\Response\Pokemon;

use App\Search\Trait\GetNameTrait;

readonly class PokemonTypeDto
{
    use GetNameTrait;

    public function __construct(
        private string $name
    ) {}

    public function getName(): string
    {
        return $this->name;
    }
}
