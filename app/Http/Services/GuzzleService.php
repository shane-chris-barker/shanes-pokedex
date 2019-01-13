<?php
namespace App\Http\Services;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class GuzzleService
{
    private $guzzleClient;
    public function __construct($baseUrl)
    {
        $this->guzzleClient = new Client(['base_uri' => $baseUrl]);

    }

    //Perform a GET request against the pokeApi and catch any exceptions
    public function callPokeApi($endpoint, $searchParam, $searchValue)
    {
        $queryString = "{$endpoint}/$searchValue";
        try {
            $response = $this->guzzleClient->get($queryString);
        } catch (ClientException $e) {
            return false;
        }
        return $response = json_decode($response->getBody()->getContents());
    }
}
