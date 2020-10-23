<?php

namespace packages\UseCase\Schedule\Send;

interface ScheduleSendUseCaseInterface
{
    /**
     * @return ScheduleSendResponse
     */
    public function handle();
}
