<?php

namespace AppBundle\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as GuzzleRequest;

/**
 * Class ApiManager
 * @package AppBundle\Service
 */
class ApiManager
{
    private $client;

    public function __construct($redmine_password, $redmine_username)
    {
        $this->client = new Client([
            'base_uri' => 'https://redmine.ekreative.com/',
                'auth' => [$redmine_username, $redmine_password],
                'headers' => ['Content-Type' => 'application/json'],
        ]);
    }

    /**
     * @param string $url
     * @param string $method
     * @return mixed
     */
    public function get($url, $method = 'GET')
    {
        $request = new GuzzleRequest($method, $url);
        $response = $this->client->send($request);
        return json_decode($response->getBody(), true);
    }

    /**
     * @param string $url
     * @param array $body
     */
    public function post($url, array $body)
    {
        $this->client->post($url, [
            'json' => $body,
        ]);
    }

}