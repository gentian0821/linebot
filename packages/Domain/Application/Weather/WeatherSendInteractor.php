<?php

namespace packages\Domain\Application\Weather;

use packages\UseCase\Weather\WeatherUseCase;

class WeatherSendInteractor
{
    private $weatherUseCase;

    public function __construct(WeatherUseCase $weatherUseCase) {
        $this->weatherUseCase = $weatherUseCase;
    }

    public function handle(): void
    {
        $this->weatherUseCase->send();
    }
}
