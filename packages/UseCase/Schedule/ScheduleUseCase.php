<?php

namespace packages\UseCase\Schedule;

use packages\Infrastructure\Schedule\ScheduleRepositoryInterface;
use Google_Client;
use Google_Service_Calendar;
use Illuminate\Support\Facades\Config;

class ScheduleUseCase
{
    private $scheduleRepository;

    public function __construct(ScheduleRepositoryInterface $scheduleRepository)
    {
        $this->scheduleRepository = $scheduleRepository;
    }

    public function send()
    {
        $json = json_decode(Config::get('const.google_api_credential'), true);
        $googleClient = new Google_Client();
        $googleClient->setApplicationName('calendar');
        $googleClient->setAuthConfig($json);
        $googleClient->setAccessType('offline');
        $googleClient->setScopes(Google_Service_Calendar::CALENDAR_READONLY);

        $events = $this->scheduleRepository->fetchEvents(new Google_Service_Calendar($googleClient));

        $this->scheduleRepository->sendMessage($events);
    }
}
