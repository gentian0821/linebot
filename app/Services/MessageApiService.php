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
        $options = [
            'json' => [
                "replyToken" => $replyToken,
                "messages" => $messages
            ],
            'headers' => $this->headers,
        ];

        return $this->client->request('POST', Config::get('const.line_reply_api'), $options);
    }

    /**
     * @param $messages
     * @param $send_to
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function push($messages, $send_to)
    {
        $options = [
            'json' => [
                "to" => $send_to,
                "messages" => $messages
            ],
            'headers' => $this->headers,
        ];

        return $this->client->request('POST', Config::get('const.line_push_api'), $options);
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
var_dump(printf(Config::get('const.line_content_api'), $message_id));
        return $this->client->request('GET', printf(Config::get('const.line_content_api'), $message_id), $options);
    }
}
