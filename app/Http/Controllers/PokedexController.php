<?php

namespace App\Http\Controllers;

use App\Http\Requests\PokedexSearchRequest;
use App\Http\Helpers\SearchHelper;
use Session;


class PokedexController extends Controller
{
    /** @var SearchHelper $searchHelper */
    private $searchHelper;


    /**
     * PokedexController constructor.
     * @param SearchHelper $searchHelper
     */
    public function __construct(SearchHelper $searchHelper)
    {
        $this->searchHelper = $searchHelper;
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

        $data = $this->searchHelper->handleSearch($pokedexSearchRequest);

        if (false === $data['success']) {
            // the search query doesn't exist or something fell over. Send back with errors.
            return redirect()->back()->withInput()->withErrors($data['errorReason']);
        }

        // send the user back to the view action with the data we have.
        return Redirect()
            ->to('/')
            ->withInput()
            ->with('cachedResult', $data['cachedResult'])
            ->with('results', json_encode($data))
            ->with('search', $pokedexSearchRequest->input('search'));
    }
}
