<?php

namespace App\Services;

use Google_Client;
use Google_Service_Calendar;
use Illuminate\Support\Facades\Config;

class CalendarApiService
{
    private $client;

    private $service;

    public function __construct()
    {
        $json = json_decode(Config::get('const.google_api_credential'), true);
        $this->client = new Google_Client();
        $this->client->setApplicationName('calendar');
        $this->client->setAuthConfig($json);
        $this->client->setAccessType('offline');
        $this->client->setScopes(Google_Service_Calendar::CALENDAR_READONLY);
        $this->service = new Google_Service_Calendar($this->client);
    }

    /**
     * @return \Google_Service_Calendar_Event
     */
    public function events()
    {
        $params = array(
            'maxResults' => 10,
            'orderBy' => 'startTime',
            'singleEvents' => true,
            'timeMax' => date('c',strtotime(date('Y-m-d 23:59:59'))),
            'timeMin' => date('c',strtotime(date('Y-m-d 00:00:00'))),//2019年1月1日以降の予定を取得対象
        );

        $results = $this->service->events->listEvents(Config::get('const.calendar_id'), $params);

        return $results->getItems();
    }
}
