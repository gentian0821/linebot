<?php

namespace packages\Domain\Domain\Weather;

interface WeatherRepositoryInterface
{
    public function forecasts(): array;

    public function sendMessage(array $weather_info): void;
}
