<?php

namespace packages\Domain\Application\Schedule;

use packages\UseCase\Schedule\ScheduleUseCase;

class ScheduleSendInteractor
{
    private $scheduleUseCase;

    public function __construct(ScheduleUseCase $scheduleUseCase)
    {
        $this->scheduleUseCase = $scheduleUseCase;
    }

    public function handle()
    {
        $this->scheduleUseCase->send();
    }
}
