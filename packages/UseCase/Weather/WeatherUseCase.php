<?php

namespace packages\UseCase\Weather;

use packages\Infrastructure\Weather\OpenWeatherRepositoryInterface;
use packages\Infrastructure\Weather\WeatherRepositoryInterface;

class WeatherUseCase
{
    private $weatherRepository;
    private $openWeatherRepository;

    public function __construct(
        WeatherRepositoryInterface $weatherRepository,
        OpenWeatherRepositoryInterface $openWeatherRepository
    ) {
        $this->weatherRepository = $weatherRepository;
        $this->openWeatherRepository = $openWeatherRepository;
    }

    public function send()
    {
        $weather_info = $this->openWeatherRepository->forecasts();

        $this->weatherRepository->sendMessage($weather_info);
    }

}
