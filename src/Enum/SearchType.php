<?php

namespace App\Enum;

enum SearchType: string
{
    case Pokemon = 'pokemon';
    case Item = 'item';
    case Location = 'location';
}
