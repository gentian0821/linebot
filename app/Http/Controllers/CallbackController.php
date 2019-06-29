<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class CallbackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['ok']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $param = $request->input();

        Log::info($param);

        $client = new Client(['base_uri' => Config::get('const.line_base_uri')]);

        $response = $client->request(
            'POST',
            Config::get('const.line_reply_api'),
            [
                'json' => [
                    "replyToken" => $param["events"][0]["replyToken"],
                    "messages" => [
                        $this->reply_info($param['events'][0]['message']["text"])
                    ]
                ],
                'headers' => [
                    'Content-Type' => 'application/json; charser=UTF-8',
                    'Authorization' => 'Bearer ' . Config::get('const.line_access_token'),
                ]
            ]
        );

        Log::info($response);

        return response()->json(['ok']);
    }

    private function reply_info($push_text) {
        $result = [
            "type" => "text",
            "text" => $push_text
        ];

        if (strpos($push_text, '登録') !== false) {
            $result['text'] = '登録したよー！';
        }

//        $result = [
//            "type" => "sticker",
//            "packageId" => '11538',
//            'stickerId' => '51626531'
//        ];

        return $result;
    }
}
