<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;

class MessageApiService
{
    private $client;

    private $headers;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => Config::get('const.line_base_uri')]);
        $this->headers = [
            'Content-Type' => 'application/json; charser=UTF-8',
            'Authorization' => 'Bearer ' . Config::get('const.line_access_token'),
        ];
    }

    /**
     * @param $messages
     * @param $replyToken
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function reply($messages, $replyToken)
    {
        $reply_api = Config::get('const.line_reply_api');
        $options = [
            'json' => [
                "replyToken" => $replyToken,
                "messages" => $messages
            ],
            'headers' => $this->headers,
        ];

        return $this->client->request('POST', $reply_api, $options);
    }

    /**
     * @param $messages
     * @param $to
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function push($messages, $to)
    {
        $push_api = Config::get('const.line_push_api');
        $options = [
            'json' => [
                "to" => $to,
                "messages" => $messages
            ],
            'headers' => $this->headers,
        ];

        return $this->client->request('POST', $push_api, $options);
    }
}
