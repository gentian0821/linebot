<?php

namespace packages\Infrastructure\Weather;

interface WeatherRepositoryInterface
{
    public function sendMessage(array $weather_info): void;
}
