<?php
namespace App\Http\Helpers;

use App\Http\Requests\PokedexSearchRequest;
use App\Http\Services\GuzzleService;
use Illuminate\Support\Facades\Redis;

class SearchHelper
{
    /** @var GuzzleService $guzzleService */
    private $guzzleService;

    /** @var Redis $redis */
    private $redis;

    /** @var boolean $useCaching */
    private $useCaching;

    /** @var array  */
    const SEARCH_TYPES = [
        'name'      => 'pokemon',
        'move'      => 'move',
        'item'      => 'item',
        'location'  => 'location',
        'species'   => 'species',
    ];

    /**
     * SearchHelper constructor.
     * @param GuzzleService $guzzleService
     * @param Redis $redis
     */
    public function __construct(GuzzleService $guzzleService, Redis $redis)
    {
        $this->guzzleService = $guzzleService;

        // if we have caching enabled, we'll fire up Redis
        $this->useCaching = env( 'CACHE_WITH_REDIS', false);
        if ($this->useCaching === true) {
            $this->redis = $redis;
        }
    }

    /**
     * Perform a search against the pokeApi
     * @param PokedexSearchRequest $pokedexSearchRequest
     * @return array
     */
    public function handleSearch(PokedexSearchRequest $pokedexSearchRequest) : array
    {
        // set the URL based on the type of search we are performing
        $searchEndpoint = $this->setUrl($pokedexSearchRequest->input('search'));
        $data = [];
        $data['cachedResult'] = false;

        if (false === $searchEndpoint) {
            $data['success']        = false;
            $data['errorReason']    = 'An error occurred - Please try again later';
        }

        // now we have set the url, we'll check the Redis cache to see if we have a key that
        // matches it.
        if ($this->useCaching === true) {
            $cache = $this->redis::get($pokedexSearchRequest->input('search_param'));
        }
        // the cache is empty or we aren't using caching at all so we'll need to call the api
        if(empty($cache) || $this->useCaching === false) {
            $searchParam    = str_replace(' ' , '-', $pokedexSearchRequest->input('search_param'));
            $data['main'] = $this->guzzleService->callPokeApi
            (
                $searchEndpoint,
                strtolower($searchParam)
            );

            // the search term threw a 404
            if (false === $data['main'] ) {
                $data['success'] = false;
                $data['errorReason'] = 'No results were found for the search term';
                return $data;
            }

            // if this search was for a pokemon, we can search additional endpoints for more information
            if ($searchEndpoint === $this::SEARCH_TYPES['name']) {
                // get additional species info
                $data['extra']  = $this->searchForExtraPokemonData($data['main']);
                // get additional forms info
                $data['forms']  = $this->searchForExtraForms($data['extra']['species_info']);
            } elseif ($searchEndpoint === $this::SEARCH_TYPES['location']) {
                $data['extra'] = $this->searchForExtraLocationData($data['main']->areas, $data['main']->region);
            }

            // caching is on - cache the result
            if ($this->useCaching === true) {
                $this->redis::set($searchParam, json_encode($data));
            }

        } else {
            // we had results in the cache for this search so use that instead
            $data['cachedResult'] = true;
            $data = json_decode($cache);

        }

        $data['success'] = true;

        return $data;

    }

    /**
     * @param $searchType
     * @return bool|mixed
     *
     * Set the endpoint to call based on the type of search being requested
     */
    private function setUrl($searchType)
    {
        if (array_key_exists($searchType, self::SEARCH_TYPES)) {
            return $endpoint = self::SEARCH_TYPES[$searchType];
        }

        return false;
    }

    /**
     * @param $pokemonData
     * @return array
     *
     * Search for extra information about a species of pokemon - only called when searching for a pokemon
     * by name / id
     */
    private function searchForExtraPokemonData($pokemonData) : array
    {
        // get the extra info about the pokemon species
        $data['species_info'] = $this->guzzleService->callPokeApi
        (
            $pokemonData->species->url,
            null,
            $fullUrl = true
        );

        // get the evolution line
        $evolutionInfo = $this->guzzleService->callPokeApi
        (
            $data['species_info']->evolution_chain->url,
            null,
            $fullUrl = true
        );

        if (false === empty($evolutionInfo->chain->evolves_to)) {
            // work out where this pokemon is in the evolution chain
            if(array_key_exists(0, $evolutionInfo->chain->evolves_to[0]->evolves_to)
                && false === empty( $evolutionInfo->chain->evolves_to[0]->evolves_to[0])) {
                // this is a mon that has 3 evolutions
                $evolution[1] = $evolutionInfo->chain->species->name;
                $evolution[2] = $evolutionInfo->chain->evolves_to[0]->species->name;
                $evolution[3] = $evolutionInfo->chain->evolves_to[0]->evolves_to[0]->species->name;
            } else {
                // this in a chain of two
                $evolution[1] = $evolutionInfo->chain->species->name;
                $evolution[2] = $evolutionInfo->chain->evolves_to[0]->species->name;
            }
        } else {
            // no evolutions
            $data['evolution_info'] = false;
            return $data;
        }

        // now we know all about the evolutions, go and get basic information for them
        $searchEndpoint = $this->setUrl('name');
        $i = 1;
        foreach ($evolution as $pokemonName) {
            $currentEvolution = null;
            // we dont need to fetch data that we already have...
            if ($pokemonName !== $pokemonData->name) {
                $currentEvolution = $this->guzzleService->callPokeApi
                (
                    $searchEndpoint,
                    $pokemonName,
                    $fullUrl = false
                );
            }

            // store either the evolution in the array or the current value if the evolution
            // is equal to the pokemon being searched for.
            $data['evolution_info'][$i]['name']     = isset($currentEvolution->name)
                ? $currentEvolution->name : $pokemonData->name;
            $data['evolution_info'][$i]['sprites']  = isset($currentEvolution->sprites)
                ? $currentEvolution->sprites : $pokemonData->sprites;
            $i++;
        }

        return $data;
    }

    /**
     * @param $pokemonData
     * @return array
     *
     * Search for extra pokemon form data - only called when searching for pokemon by name/id
     */
    private function searchForExtraForms($pokemonData) : array
    {
        $i = 1;
        $data['varieties'] = false;
        // loop the forms associated with this pokemon
        foreach ($pokemonData->varieties as $variety) {

            // if this is not the default form which we already have info on, go and
            // fetch the information about this form
            if (false === $variety->is_default) {
                $regionData = $this->guzzleService->callPokeApi
                (
                    $variety->pokemon->url,
                    strtolower($variety->pokemon->name),
                    $fullUrl = true
                );

                // use the previous result get the more detailed information about the form
                foreach ($regionData->forms as $form) {
                    $formData = $this->guzzleService->callPokeApi
                    (
                        $form->url,
                        strtolower($form->name),
                        $fullUrl = true
                    );

                    // ge the data we want for our view
                    $data['varieties'][$formData->form_name]['name']        = str_replace(
                        '-', ' ', $formData->name
                    );
                    $data['varieties'][$formData->form_name]['sprites']     = $formData->sprites;
                    $data['varieties'][$formData->form_name]['is_mega']     = $formData->is_mega;
                    $data['varieties'][$formData->form_name]['form_name']   = $formData->form_name;
                    $i++;
                }
            }
        }

        return $data;
    }


    /**
     * @param $areaData
     * @param $regionData
     * @return array
     */
    private function searchForExtraLocationData($areaData, $regionData) : array
    {
        // check the area this location belongs to and grab some extra data about it
        $responseData = [];
        $locationAreaData = $this->guzzleService->callPokeApi('location-area', $areaData[0]->name, false);
        $responseData['area']['name'] = $locationAreaData->name;
        if (count($locationAreaData->pokemon_encounters) > 0) {
            $pokemonEncounterData = [];
            $i = 0;
            foreach ($locationAreaData->pokemon_encounters as $pokemonEncounter) {
                $pokemonEncounterData[$i]['name'] = $pokemonEncounter->pokemon->name;
                $x = 0;
                foreach ($pokemonEncounter->version_details as $versionDetail ) {
                    $pokemonEncounterData[$i]['versions'][$x]['versionName'] = $versionDetail->version->name;
                    $x++;
                }
                $responseData['pokemonEncounters'] = $pokemonEncounterData;
                $i++;
            }
        }

        $regionAreaData = $this->guzzleService->callPokeApi('region', $regionData->name, false);
        $responseData['region']['name'] = $regionData->name;

        // get any pokedexes that have featured this region
        if (count($regionAreaData->pokedexes) > 0) {
            $pokedexes = [];
            $i = 0;
            foreach ($regionAreaData->pokedexes as $pokedex) {
                $pokedexes[$i]['name'] = $pokedex->name;
                $i++;
            }
        }

        // and get any versions of the game that this area appears in - These are formatted like 'red-blue' from the
        // api, so we'll break those up into their individual parts
        $versions = [];
        $x = 0;
        foreach ($regionAreaData->version_groups as $version) {
            // check for 2 versions in one response - ignore lets-go
            if (strstr($version->name, '-') && $version->name !==  "lets-go") {
                $twoVersions = explode('-', $version->name);
                $versions[$x]['name'] = $twoVersions[0];
                $x++;
                $versions[$x]['name'] = $twoVersions[1];
            } else {
                // standard
                $versions[$x]['name'] = $version->name;
            }
            $x++;
        }

        $responseData['region']['versions'] = $versions;

        return $responseData;
    }
}
