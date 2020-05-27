<?php

namespace packages\Domain\Application\Weather;

use App\Services\MessageApiService;
use packages\Domain\Domain\Weather\WeatherRepositoryInterface;
use packages\UseCase\Weather\Send\WeatherSendUseCaseInterface;


class WeatherSendInteractor implements WeatherSendUseCaseInterface
{
    /**
     * @var WeatherRepositoryInterface
     */
    private $weatherRepository;

    /**
     * WeatherSendInteractor constructor.
     * @param WeatherRepositoryInterface $weatherRepository
     */
    public function __construct(WeatherRepositoryInterface $weatherRepository)
    {
        $this->weatherRepository = $weatherRepository;
    }

    /**
     * @throws \Google_Exception
     */
    public function handle()
    {
        $weather_info = $this->weatherRepository->forecasts();

        $this->weatherRepository->sendMessage(new MessageApiService(), $weather_info);
    }
}
