<?php
namespace App\Http\Helpers;

class SearchHelper
{
    const SEARCH_TYPES = [
        'name'      => 'pokemon',
        'move'      => 'move',
        'item'      => 'item',
        'location'  => 'location',
        'species'   => 'species',
    ];

    /**
     * @param $searchType
     * @return bool|mixed
     *
     * Set the endpoint to call based on the type of search being requested
     */
    public function setUrl($searchType)
    {
        if (array_key_exists($searchType, self::SEARCH_TYPES)) {
            return $endpoint = self::SEARCH_TYPES[$searchType];
        }

        return false;
    }
}
