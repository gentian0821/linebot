<?php

namespace packages\Infrastructure\Schedule;

use Google_Service_Calendar;

interface ScheduleRepositoryInterface
{
    public function fetchEvents(Google_Service_Calendar $service);

    public function sendMessage(array $events);
}
