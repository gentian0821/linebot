<?php

namespace App\Http\Controllers;

use packages\UseCase\Weather\Send\WeatherSendUseCaseInterface;

class PushWeatherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param WeatherSendUseCaseInterface $interactor
     *
     * @return \Illuminate\Http\Response
     */
    public function index(WeatherSendUseCaseInterface $interactor)
    {
        $interactor->handle();

        return response()->json(['ok']);
    }
}
