<?php

namespace App\Http\Controllers;

use App\Services\WeatherApiService;
use App\Services\MessageApiService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class PushWeatherController extends Controller
{
    private $message_api;

    public function __construct(MessageApiService $message_api)
    {
        $this->message_api = $message_api;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $weather_api = new WeatherApiService();


        $weather_info = $weather_api->forecasts();

        if (!$weather_info) {
            return response()->json(['ok']);
        }

        Log::info($weather_info);

        $image_url = str_replace('http://','https://', $weather_info['forecasts'][0]['image']['url']);
//        $image = file_get_contents($weather_info['forecasts'][0]['image']['url']);
//        file_put_contents('./img/' . )
        $message = [
            [
                'type' => 'text',
                'text' => $weather_info['title'],
            ],
            [
                'type' => 'image',
                'originalContentUrl' => $image_url,
                'previewImageUrl'    => $image_url
            ],
            [
                'type' => 'text',
                'text' => $weather_info['forecasts'][0]['telop'],
            ],
            [
                'type' => 'text',
                'text' => $weather_info['description']['text'],
            ],
        ];

        $this->message_api->push($message, Config::get('const.calendar_send_to'));

        return response()->json(['ok']);
    }
}
