<?php

namespace packages\Infrastructure\Weather;

interface OpenWeatherRepositoryInterface
{
    public function forecasts(): array;
}
