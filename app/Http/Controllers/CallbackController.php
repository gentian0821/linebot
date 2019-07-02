<?php

namespace App\Http\Controllers;

use App\Services\MessageApiService;
use App\Services\AnalyzeMessageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CallbackController extends Controller
{
    private $message_api;

    private $analyze_message;

    public function __construct(MessageApiService $message_api, AnalyzeMessageService $analyze_message)
    {
        $this->message_api = $message_api;
        $this->analyze_message = $analyze_message;
    }

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

        $messages[] = $this->analyze_message->reply_message($param['events'][0]);

        $response = $this->message_api->reply($messages, $param["events"][0]['replyToken']);

        Log::info($response->getBody());

        return response()->json(['ok']);
    }

}
