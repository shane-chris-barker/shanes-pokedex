<?php

namespace App\Search\Dto\Response\Pokemon;

readonly class PokemonSpritesDto
{
    public function __construct(
        private ?string $backDefault,
        private ?string $backFemale,
        private ?string $backShiny,
        private ?string $backShinyFemale,
        private ?string $frontDefault,
        private ?string $frontFemale,
        private ?string $frontShiny,
        private ?string $frontShinyFemale
    ) {}

    public function getBackDefault(): ?string
    {
        return $this->backDefault;
    }

    public function getBackFemale(): ?string
    {
        return $this->backFemale;
    }

    public function getBackShiny(): ?string
    {
        return $this->backShiny;
    }

    public function getBackShinyFemale(): ?string
    {
        return $this->backShinyFemale;
    }

    public function getFrontDefault(): ?string
    {
        return $this->frontDefault;
    }

    public function getFrontFemale(): ?string
    {
        return $this->frontFemale;
    }

    public function getFrontShiny(): ?string
    {
        return $this->frontShiny;
    }

    public function getFrontShinyFemale(): ?string
    {
        return $this->frontShinyFemale;
    }
}
