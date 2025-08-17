<?php

namespace App\Search\Dto\Response\Pokemon;

use App\Search\Dto\Interface\SearchResultDtoInterface;
use App\Search\Dto\Response\Common\GameAppearanceDto;
use Symfony\Component\Validator\Constraints as Assert;

class PokemonResultDto implements SearchResultDtoInterface
{
    public function __construct(
        #[Assert\NotNull]
        private readonly int $id,
        #[Assert\NotNull]
        private readonly string $name,
        private readonly PokemonSpritesDto $sprites,
        private readonly string $description,
        #[Assert\All([new Assert\Type(PokemonTypeDto::class)])]
        private array $types = [],
        #[Assert\All([new Assert\Type(GameAppearanceDto::class)])]
        private array $gameAppearances = [],
        #[Assert\All([new Assert\Type(PokemonCryDto::class)])]
        private array $pokemonSounds = []
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSprites(): PokemonSpritesDto
    {
        return $this->sprites;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getTypes(): array
    {
        return $this->types;
    }

    public function addType(PokemonTypeDto $type): void
    {
        $this->types[] = $type;
    }

    public function getGameAppearances(): array
    {
        return $this->gameAppearances;
    }

    public function addGameAppearance(GameAppearanceDto $gameAppearance): void
    {
        $this->gameAppearances[] = $gameAppearance;
    }

    public function getPokemonSounds(): array
    {
        return $this->pokemonSounds;
    }

    public function addPokemonSound(PokemonCryDto $pokemonSound): void
    {
        $this->pokemonSounds[] = $pokemonSound;
    }
}
