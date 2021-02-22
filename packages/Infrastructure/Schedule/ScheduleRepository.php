<?php

namespace packages\Infrastructure\Schedule;

use Google_Service_Calendar;
use Illuminate\Support\Facades\Config;
use App\Services\MessageApiService;

class ScheduleRepository implements ScheduleRepositoryInterface
{
    private $messageApiService;

    public function __construct(MessageApiService $messageApiService)
    {
        $this->messageApiService = $messageApiService;
    }

    public function fetchEvents(Google_Service_Calendar $service): array
    {
        $params = array(
            'maxResults' => 10,
            'orderBy' => 'startTime',
            'singleEvents' => true,
            'timeMax' => date('c',strtotime(date('Y-m-d 23:59:59'))),
            'timeMin' => date('c',strtotime(date('Y-m-d 00:00:00'))),//2019年1月1日以降の予定を取得対象
        );

        $results = $service->events->listEvents(Config::get('const.calendar_id'), $params);

        return $results->getItems();
    }

    public function sendMessage(array $events): void
    {
        if (!$events) {
            return;
        }

        $result = '今日の予定だよー！';
        foreach ($events as $e) {
            if (!$e->getSummary()) {
                continue;
            }

            $result .= "\n・ " . $e->getSummary();
        }

        $this->messageApiService->push(
            [['type' => 'text', 'text' => $result]],
            Config::get('const.calendar_send_to')
        );
    }
}
