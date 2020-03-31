<?php

namespace App\Http\Controllers;

use packages\UseCase\Schedule\Send\ScheduleSendUseCaseInterface;

class ScheduleController extends Controller
{
    /**
     * @param ScheduleSendUseCaseInterface $interactor
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(ScheduleSendUseCaseInterface $interactor)
    {
        $interactor->handle();

        return response()->json(['ok']);
    }
}
