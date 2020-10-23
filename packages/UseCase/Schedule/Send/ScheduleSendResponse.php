<?php

namespace packages\UseCase\Schedule\Send;

use Google_Service_Calendar_Event;

class ScheduleSendResponse
{
    public $event;

    /**
     * ScheduleSendResponse constructor.
     * @param Google_Service_Calendar_Event $event
     */
    public function __construct(Google_Service_Calendar_Event $event)
    {
        $this->event = $event;
    }
}
