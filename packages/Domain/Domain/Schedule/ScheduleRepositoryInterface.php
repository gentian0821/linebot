<?php

namespace packages\Domain\Domain\Schedule;

use Google_Service_Calendar;
use App\Services\MessageApiService;

interface ScheduleRepositoryInterface
{
    /**
     * @param Google_Service_Calendar $service
     * @return array
     */
    public function fetchEvents(Google_Service_Calendar $service);

    /**
     * @param MessageApiService $messageService
     * @param array $events
     * @return void
     */
    public function sendMessage(MessageApiService $messageService, array $events);
}
