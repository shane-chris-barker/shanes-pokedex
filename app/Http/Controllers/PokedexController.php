<?php

namespace App\Http\Controllers;

use App\Http\Requests\PokedexSearchRequest;
use App\Http\Services\GuzzleService;
use App\Http\Helpers\SearchHelper;
use Illuminate\Support\Facades\Redis;
use Session;


class PokedexController extends Controller
{
    const API_BASE_URL = "https://pokeapi.co/api/v2/";
    private $redis;
    private $useCaching;
    private $guzzleService;
    private $searchHelper;

    /**
     * PokedexController constructor.
     */
    public function __construct()
    {
        // if we have caching enabled, we'll fire up Redis
        $this->useCaching = env('CACHE_WITH_REDIS', false);
        if ($this->useCaching === true) {
            $this->redis = new Redis();
        }

        $this->guzzleService    = new GuzzleService($this::API_BASE_URL);
        $this->searchHelper     = new SearchHelper();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     * Show the initial search page and any results which may be in the session
     */
    public function view()
    {
        $data = Session::get('results');
        // if data isset, we've just done a search and have some results
        // in our session so get the relevant view ready
        if(isset($data)) {
            $data           = json_decode($data);
            $cachedResult   = Session::get('cachedResult');
            $hasResults     = true;
            $search         = Session::get('search');
            $view           = \View::make("partials.{$search}")
                ->with('data', $data)
                ->with('cachedResult', $cachedResult);

        } else {
            // we have no data and thus no results view
            $view           = false;
            $hasResults     = false;
        }
        return view('pokedex')->with('hasResults', $hasResults)->with('results', $view);
    }

    /**
     * @param PokedexSearchRequest $pokedexSearchRequest
     * @return \Illuminate\Http\RedirectResponse
     *
     * Perform a search against the PokeApi or the Redis cache if enabled.
     */
    public function search(PokedexSearchRequest $pokedexSearchRequest)
    {
        $cachedResult   = false;

        // set the URL based on the type of search we are performing
        $searchEndpoint = $this->searchHelper->setUrl($pokedexSearchRequest->input('search'));

        if ($searchEndpoint === false) {
            // the search query doesn't exist. Send back with errors.
            return redirect()->back()->withInput()->withErrors('An error occurred. Please try again later.');
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
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors("The provided search query doesn't exist.");
            }

            // if this search was for a pokemon, we can search additional endpoints for more information
            if ($searchEndpoint === $this->searchHelper::SEARCH_TYPES['name']) {
                // get additional species info
                $data['extra']  = $this->searchForExtraPokemonData($data['main']);
                // get additional forms info
                $data['forms']  = $this->searchForExtraForms($data['extra']['species_info']);
            }

            // caching is on - cache the result
            if ($this->useCaching === true) {
                $this->redis::set($searchParam, json_encode($data));
            }

        } else {
            // we had results in the cache for this search so use that instead
            $cachedResult   = true;
            $data           = json_decode($cache);
        }

        // send the user back to the view action with the data we have.
        return Redirect()
            ->to('/')
            ->withInput()
            ->with('cachedResult', $cachedResult)
            ->with('results', json_encode($data))
            ->with('search', $pokedexSearchRequest->input('search'));
    }

    /**
     * @param $pokemonData
     * @return array
     *
     * Search for extra information about a species of pokemon - only called when searching for a pokemon
     * by name / id
     */
    private function searchForExtraPokemonData(object $pokemonData) : array
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
        $searchEndpoint = $this->searchHelper->setUrl('name');
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
    private function searchForExtraForms(object $pokemonData) : array
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
                    $data['varieties'][$formData->form_name]['name']        = str_replace('-', ' ', $formData->name);
                    $data['varieties'][$formData->form_name]['sprites']     = $formData->sprites;
                    $data['varieties'][$formData->form_name]['is_mega']     = $formData->is_mega;
                    $data['varieties'][$formData->form_name]['form_name']   = $formData->form_name;
                    $i++;
                }
            }
        }

        return $data;
    }

}
