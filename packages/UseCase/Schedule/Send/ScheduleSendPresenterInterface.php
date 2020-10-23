<?php


namespace packages\UseCase\Schedule\Send;


/**
 * Interface SchedulePresenterInterface
 * @package packages\UseCase\Schedule\Send
 */
interface ScheduleSendPresenterInterface
{
    /**
     * @param ScheduleSendResponse $outputData
     * @return mixed
     */
    public function output(ScheduleSendResponse $outputData);
}
