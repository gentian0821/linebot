<?php

namespace packages\Domain\Application\Schedule;

use App\Services\MessageApiService;
use packages\Domain\Domain\Schedule\ScheduleRepositoryInterface;
use packages\UseCase\Schedule\Send\ScheduleSendUseCaseInterface;
use Google_Client;
use Google_Service_Calendar;
use Illuminate\Support\Facades\Config;


class ScheduleSendInteractor implements ScheduleSendUseCaseInterface
{
    /**
     * @var ScheduleRepositoryInterface
     */
    private $scheduleRepository;

    /**
     * ScheduleSendInteractor constructor.
     * @param ScheduleRepositoryInterface $scheduleRepository
     */
    public function __construct(ScheduleRepositoryInterface $scheduleRepository)
    {
        $this->scheduleRepository = $scheduleRepository;
    }

    /**
     * @throws \Google_Exception
     */
    public function handle()
    {
        $json = json_decode(Config::get('const.google_api_credential'), true);
        $googleClient = new Google_Client();
        $googleClient->setApplicationName('calendar');
        $googleClient->setAuthConfig($json);
        $googleClient->setAccessType('offline');
        $googleClient->setScopes(Google_Service_Calendar::CALENDAR_READONLY);

        $events = $this->scheduleRepository->fetchEvents(new Google_Service_Calendar($googleClient));

        $this->scheduleRepository->send(new MessageApiService(), $events);
    }
}
