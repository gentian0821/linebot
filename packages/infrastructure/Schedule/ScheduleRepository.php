<?php

namespace packages\Infrastructure\Schedule;


use packages\Domain\Domain\Schedule\ScheduleRepositoryInterface;
use Google_Service_Calendar;
use Illuminate\Support\Facades\Config;
use Google_Service_Calendar_Event;
use App\Services\MessageApiService;

class ScheduleRepository implements ScheduleRepositoryInterface
{
    /**
     * @param Google_Service_Calendar $service
     * @return array
     */
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

    /**
     * @param MessageApiService $messageService
     * @param array $events
     */
    public function send(MessageApiService $messageService, array $events): void
    {
        $result = '今日の予定だよー！';
        foreach ($events as $e) {
            if (!$e->getSummary()) {
                continue;
            }

            $result .= "\n・ " . $e->getSummary();
        }

        $messageService->push($result, Config::get('const.calendar_send_to'));
    }
}
