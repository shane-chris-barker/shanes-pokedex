<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
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

    public function __construct()
    {
        // if we have caching enabled, we'll fire up Redis
        $this->useCaching = env('CACHE_WITH_REDIS', false);
        if ($this->useCaching === true) {
            $this->redis = new Redis();
        }
    }

    public function view()
    {
        $data = Session::get('results');
        // if data isset, we've just done a search and have some results
        // in our session so get the relevant view ready
        if(isset($data)) {
            $data = json_decode($data);
            $cachedResult = Session::get('cachedResult');
            $hasResults = true;
            $search     = Session::get('search');
            $view       = \View::make("partials.{$search}")
                ->with('data', $data)
                ->with('cachedResult', $cachedResult);

        } else {
            // we have no data and thus no results view
            $data           = false;
            $view           = false;
            $hasResults     = false;
            $cachedResult   = false;
        }
        return view('pokedex')->with('hasResults', $hasResults)->with('results', $view);
    }

    public function search(PokedexSearchRequest $pokedexSearchRequest)
    {

        $cachedResult   = false;
        $searchHelper   = new SearchHelper();
        $guzzleService  = new GuzzleService(SELF::API_BASE_URL);

        // set the URL based on the type of search we are performing
        $searchEndpoint = $searchHelper->setUrl($pokedexSearchRequest->input('search'));

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
            $data = $guzzleService->callPokeApi
            (
                $searchEndpoint,
                $pokedexSearchRequest->input('search'),
                strtolower($searchParam)
            );

            // the search term threw a 404
            if (false === $data ) {
                return redirect()->back()->withInput()->withErrors("The provided search query doesn't exist.");
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

        return Redirect()
            ->to('/')
            ->withInput()
            ->with('cachedResult', $cachedResult)
            ->with('results', json_encode($data))
            ->with('search', $pokedexSearchRequest->input('search'));
    }
}
