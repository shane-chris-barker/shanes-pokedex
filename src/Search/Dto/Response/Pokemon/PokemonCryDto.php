<?php

namespace App\Search\Dto\Response\Pokemon;

readonly class PokemonCryDto
{
    public function __construct(
        private string $soundName,
        private string $url,
    ) {}

    public function getSoundName(): string
    {
        return $this->soundName;
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}
