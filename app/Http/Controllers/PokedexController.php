<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\PokedexSearchRequest;
use App\Http\Services\GuzzleService;
use App\Http\Helpers\SearchHelper;
use Session;


class PokedexController extends Controller
{
    const API_BASE_URL = "https://pokeapi.co/api/v2/";

    public function view()
    {
        $data = Session::get('results');
        // if data isset, we've just done a search and have some results
        // in our session so get the relevant view ready
        if(isset($data)) {
            $hasResults = true;
            $search     = Session::get('search');
            $view       = \View::make("partials.{$search}")->with('data', $data);
        } else {
            // we have no data and thus no results view
            $data       = false;
            $view       = false;
            $hasResults = false;
        }
        // dump($data);
        return view('pokedex')->with('hasResults', $hasResults)->with('results', $view);
    }

    public function search(PokedexSearchRequest $pokedexSearchRequest)
    {

        // dd($pokedexSearchRequest->input());
        $searchHelper   = new SearchHelper();
        $guzzleService  = new GuzzleService(SELF::API_BASE_URL);
        $searchEndpoint = $searchHelper->setUrl($pokedexSearchRequest->input('search'));
        $searchParam    = str_replace(' ' , '-', $pokedexSearchRequest->input('search_param'));


        if ($searchEndpoint === false) {
            // the search query doesn't exist. Send back with errors.
            return redirect()->back()->withInput()->withErrors('An error occurred. Please try again later.');
        }

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

        return Redirect()
        ->to('/')
        ->withInput()
        ->with('results', $data)
        ->with('search', $pokedexSearchRequest->input('search'));
    }
}
