<?php
namespace App\Http\Services;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class GuzzleService
{
    private $guzzleClient;

    /**
     * GuzzleService constructor.
     * @param $baseUrl
     */
    public function __construct($baseUrl)
    {
        $this->guzzleClient = new Client(['base_uri' => $baseUrl]);
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
