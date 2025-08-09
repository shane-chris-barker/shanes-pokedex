<?php
namespace App\Http\Services;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class GuzzleService
{
    /** @var array */
    const CLIENT_DEFAULTS = ['base_uri' => "https://pokeapi.co/api/v2/"];

    /** @var Client $guzzleClient */
    private $guzzleClient;

    /**
     * GuzzleService constructor.
     */
    public function __construct()
    {
        $this->guzzleClient = new Client(self::CLIENT_DEFAULTS);
    }

    /**
     * @param $endpoint
     * @param null $searchValue
     * @param bool $fullUrl
     * @return bool|mixed
     *
     * Use the Guzzle client to query the PokeApi
     */
    public function callPokeApi($endpoint, $searchValue = null, $fullUrl = false)
    {
        if (false === $fullUrl) {
            $queryString = "{$endpoint}/$searchValue";
        } else {
            $queryString = $endpoint;
        }

        try {
            $response = $this->guzzleClient->get($queryString);
        } catch (ClientException $e) {
            return false;
        }
        return $response = json_decode($response->getBody()->getContents());
    }
}
