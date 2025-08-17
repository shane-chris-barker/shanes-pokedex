<?php

namespace App\Search\Trait;

trait GetNameTrait
{
    public function getFriendlyName(): string
    {
        return ucwords(str_replace(['-', ' '], ' ', $this->getName()));
    }

    public function getSlugName(): string
    {
        return strtolower(
            str_replace(' ', '-', $this->getFriendlyName())
        );
    }
}
