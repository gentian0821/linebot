<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class ImageRecognitionApiService
{
    private $client;

    private $headers;

    private $api_key;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => Config::get('const.cloud_vision_base_uri')]);
        $this->headers = [
            'Content-Type' => 'application/json; charser=UTF-8',
        ];
        $this->api_key = Config::get('const.cloud_vision_api_key');
    }

    /**
     * @param $content
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function annotate($content)
    {
        $request_json = [
            "requests" => [
                [
                    "image" => [
                        "content" => base64_encode($content)
                    ],
                    "features" => [
                        [
                            "type" => "TEXT_DETECTION" ,
                            "maxResults" => 3 ,
                        ] ,
                    ],
                ],
            ],
        ];

        $options = [
            'json' => $request_json,
            'headers' => $this->headers,
        ];
Log::info(Config::get('const.cloud_vision_annotate_api') . '?key=' . $this->api_key);
        return $this->client->request(
            'POST',
            Config::get('const.cloud_vision_annotate_api') . '?key=' . $this->api_key,
            $options
        );
    }
}
