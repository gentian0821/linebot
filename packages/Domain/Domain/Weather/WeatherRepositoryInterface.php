<?php

namespace packages\Domain\Domain\Weather;

use App\Services\MessageApiService;

interface WeatherRepositoryInterface
{
    /**
     * @return array
     */
    public function forecasts(): array;

    /**
     * @param MessageApiService $messageService
     * @param array $weather_info
     */
    public function sendMessage(MessageApiService $messageService, array $weather_info): void;
}
