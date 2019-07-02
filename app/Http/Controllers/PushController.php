<?php

namespace App\Http\Controllers;

use App\Services\MessageApiService;
use App\Task;
use Illuminate\Support\Facades\Log;

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

        Log::info(print_r($tasks, true));
        $message_objects = [];
        foreach ($tasks as $task) {
            $message_objects[$task->send_to][] = [
                'type' => 'text',
                'text' => $task->send_message,
            ];
        }
Log::info(print_r($message_objects, true));
        foreach ($message_objects as $send_to => $messages) {
            $response = $this->message_api->push($messages, $send_to);
            Log::info(print_r($response, true));
        }

        return response()->json(['ok']);
    }
}
