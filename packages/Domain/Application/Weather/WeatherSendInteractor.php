<?php

namespace packages\Domain\Application\Weather;

use App\Services\MessageApiService;
use packages\Domain\Domain\Weather\WeatherRepositoryInterface;
use packages\UseCase\Weather\Send\WeatherSendUseCaseInterface;


class WeatherSendInteractor implements WeatherSendUseCaseInterface
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
