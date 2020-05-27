<?php

namespace packages\Infrastructure\Weather;

use GuzzleHttp\Client;
use packages\Domain\Domain\Weather\WeatherRepositoryInterface;
use App\Services\MessageApiService;
use Illuminate\Support\Facades\Config;

class WeatherRepository implements WeatherRepositoryInterface
{
    /**
     * @return array
     */
    public function forecasts(): array
    {
        $options = [
            'headers' => [
                'Content-Type' => 'application/json; charser=UTF-8',
            ],
        ];

        $client = new Client(['base_uri' => 'http://weather.livedoor.com']);

        $response = $client->request('GET', '/forecast/webservice/json/v1?city=130010', $options);

        return json_decode($response->getBody(), true);
    }

    /**
     * @param MessageApiService $messageService
     * @param array $weather_info
     */
    public function sendMessage(MessageApiService $messageService, array $weather_info): void
    {
        if (!$weather_info) {
            return;
        }

        $image_url = $weather_info['forecasts'][0]['image']['url'];
        $base_name = basename($image_url);
        $image_path = './img/' . $base_name;

        if (!file_exists($image_path)) {
            $image = file_get_contents($image_url);
            file_put_contents($image_path, $image);
        }

        $message = [
            [
                'type' => 'text',
                'text' => $weather_info['title'],
            ],
            [
                'type' => 'image',
                'originalContentUrl' => 'https://linebot-fayc4.herokuapp.com/img/' . $base_name,
                'previewImageUrl'    => 'https://linebot-fayc4.herokuapp.com/img/' . $base_name,
            ],
            [
                'type' => 'text',
                'text' => $weather_info['forecasts'][0]['telop'],
            ],
        ];

        $messageService->push($message, Config::get('const.calendar_send_to'));

    }
}
