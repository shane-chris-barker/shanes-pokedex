<?php
namespace App\Http\Helpers;


class SearchHelper
{
    const SEARCH_TYPES = [
        'name'      => 'pokemon/',
        'move'      => 'move/',
        'item'      => 'item/',
        'location'  => 'location/'
    ];
    // function sets the API endpoint to call based on the searchType
    public function setUrl($searchType)
    {
        if (array_key_exists($searchType, self::SEARCH_TYPES)) {
            return $endpoint = self::SEARCH_TYPES[$searchType];
        }

        return false;

    }
}
