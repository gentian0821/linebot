<?php

namespace App\Http\Controllers;

use App\Services\MessageApiService;
use App\Task;

class PushController extends Controller
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
        $tasks = Task::where('reserved_at', date('Y-m-d H:00:00'))->get();

        Log::info('LINE_ACCESS_TOKEN: ' . env('LINE_ACCESS_TOKEN'));

        $message_objects = [];
        foreach ($tasks as $task) {
            $message_objects[$task->send_to][] = [
                'type' => 'text',
                'text' => $task->send_message,
            ];
        }

        foreach ($message_objects as $send_to => $messages) {
            $this->message_api->push($messages, $send_to);
        }

        return response()->json(['ok']);
    }
}
