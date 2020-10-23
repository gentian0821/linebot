<?php

namespace packages\UseCase\Weather\Send;

interface WeatherSendUseCaseInterface
{
    /**
     * @return array
     */
    public function handle();
}
