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
     * @param Google_Service_Calendar_Event $event
     * @return Google_Service_Calendar_Event
     */
    public function send(MessageApiService $messageService, Google_Service_Calendar_Event $event);
}
