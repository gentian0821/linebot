<?php

namespace packages\Domain\Application\Weather;

use packages\Infrastructure\Weather\WeatherRepositoryInterface;

class WeatherSendInteractor
{
    private $weatherRepository;

    public function __construct(WeatherRepositoryInterface $weatherRepository) {
        $this->weatherRepository = $weatherRepository;
    }

    public function handle()
    {
        $weather_info = $this->weatherRepository->forecasts();

        $this->weatherRepository->sendMessage($weather_info);
    }
}
