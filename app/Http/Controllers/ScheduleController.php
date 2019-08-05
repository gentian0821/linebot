<?php

namespace App\Http\Controllers;

use App\Services\CalendarApiService;
use App\Services\MessageApiService;
use Google_Service_Calendar_Event;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class ScheduleController extends Controller
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
        $calendar_api = new CalendarApiService();

        /** @var Google_Service_Calendar_Event[] $events */
        $events = $calendar_api->events();

        if (!$events) {
            return response()->json(['ok']);
        }

        $result = '今日の予定だよー！';
        foreach ($events as $event) {
            if (!$event->getSummary()) {
                continue;
            }

            $result .= "\n・ " . $event->getSummary();
        }

        Log::info($result);
        Log::info($event->getSummary());
        $message = [
            [
                'type' => 'text',
                'text' => $result,
            ]
        ];

        $this->message_api->push($message, Config::get('const.calendar_send_to'));

        return response()->json(['ok']);
    }
}
