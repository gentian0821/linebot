<?php

namespace packages\Infrastructure\Weather;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;

class OpenWeatherRepository implements OpenWeatherRepositoryInterface
{
    private $client;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => Config::get('const.open_weather_base_url')]);
    }

    public function forecasts(): array
    {
        $options = [
            'headers' => [
                'Content-Type' => 'application/json; charser=UTF-8',
            ],
        ];

        $weather_api = Config::get('const.open_weather_api_weather') . '?zip=112-0011,JP&units=metric&lang=ja&APPID=';

        $api_key = Config::get('const.open_weather_api_key');

        $response = $this->client->request('GET', $weather_api . $api_key, $options);

        return json_decode($response->getBody(), true);
    }
}
