<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;

class MessageApiDataService
{
    private $client;

    private $headers;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => Config::get('const.line_data_base_uri')]);
        $this->headers = [
            'Content-Type' => 'application/json; charser=UTF-8',
            'Authorization' => 'Bearer ' . Config::get('const.line_access_token'),
        ];
    }

    /**
     * @param $messages
     * @param $send_to
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function contents($message_id)
    {
        unset($this->headers['Content-Type']);

        $options = [
            'headers' => $this->headers,
        ];

        return $this->client->request('GET', sprintf(Config::get('const.line_content_api'), $message_id), $options);
    }
}
