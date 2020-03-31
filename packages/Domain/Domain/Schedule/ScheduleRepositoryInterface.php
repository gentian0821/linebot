<?php

namespace packages\Domain\Domain\Schedule;

use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use App\Services\MessageApiService;

interface ScheduleRepositoryInterface
{
    /**
     * @param Google_Service_Calendar $service
     * @return Google_Service_Calendar_Event
     */
    public function fetchEvents(Google_Service_Calendar $service);

    /**
     * @param MessageApiService $messageService
     * @param array $events
     * @return void
     */
    public function send(MessageApiService $messageService, array $events);
}
