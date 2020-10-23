<?php


namespace App\Http\Presenters\Schedule;

use packages\UseCase\Schedule\Send\ScheduleSendPresenterInterface;
use packages\UseCase\Schedule\Send\ScheduleSendResponse;

class ScheduleSendPresenter implements ScheduleSendPresenterInterface
{
    /**
     * @param ScheduleSendResponse $outputData
     * @return mixed
     */
    public function output(ScheduleSendResponse $outputData)
    {
        $result = '今日の予定だよー！';
        foreach ($outputData->event as $event) {
            if (!$event->getSummary()) {
                continue;
            }

            $result .= "\n・ " . $event->getSummary();
        }

        return $result;
    }
}
