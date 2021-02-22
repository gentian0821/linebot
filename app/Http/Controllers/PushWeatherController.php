<?php

namespace App\Http\Controllers;

use packages\Domain\Application\Weather\WeatherSendInteractor;

class PushWeatherController extends Controller
{
    public function index(WeatherSendInteractor $interactor)
    {
        $interactor->handle();

        return response()->json(['ok']);
    }
}
