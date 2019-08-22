<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;

class WeatherApiService
{
    private $client;

    private $headers;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'http://weather.livedoor.com']);
        $this->headers = [
            'Content-Type' => 'application/json; charser=UTF-8',
        ];
    }

    /**
     * @param $messages
     * @param $send_to
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function forecasts()
    {
        $options = [
            'headers' => $this->headers,
        ];

        return json_decode($this->client->request('GET', '/forecast/webservice/json/v1?city=130010', $options), true);
    }
}
