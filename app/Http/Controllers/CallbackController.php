<?php

namespace App\Http\Controllers;

use App\Task;
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
                        $this->reply_info($param['events'][0]['message']["text"], $param['events'][0]['source']['userId'])
                    ]
                ],
                'headers' => [
                    'Content-Type' => 'application/json; charser=UTF-8',
                    'Authorization' => 'Bearer ' . Config::get('const.line_access_token'),
                ]
            ]
        );

        Log::info($response->getBody());

        return response()->json(['ok']);
    }

    private function reply_info($push_text, $send_to)
    {
        $result = $this->regist($push_text, $send_to);
        if ($result) {
            return $result;
        }

        return [
            'type' => 'text',
            'text' => $push_text
        ];
    }

    private function regist($push_text, $send_to)
    {
        if (strpos($push_text, '登録') === false) {
            return [];
        }

        if (!preg_match('/^(\d{1,2})\/(\d{1,2}) (\d{1,2})/u',$push_text, $date_matches)) {
            return [];
        }

        if (!preg_match('/「(.*)」/u',$push_text, $message_matches)) {
            return [];
        }

        $task = new Task;
        $task->send_to = $send_to;
        $task->message = $message_matches[1];
        $task->reserved_at = sprintf('2019-%02d-%02d %02d:00:00', $date_matches[1], $date_matches[2], $date_matches[3]);

        $task->save();

        Log::info($task);

        return [
            'type' => 'text',
            'text' => '登録したよー！'
        ];
    }

    private function fetch($push_text) {
        if (strpos($push_text, 'リスト') === false) {
            return [];
        }

        $tasks = Task::where('reserved_at', '>', date('Y-m-d H:i:s'))->get();

        $message = 'お知らせ予定だよー\n';
        foreach ($tasks as $task) {
            $message .= $task->reserved_at . ' ' . $task->message . '\n';
        }

        return [
            'type' => 'text',
            'text' => $message
        ];

    }
}
