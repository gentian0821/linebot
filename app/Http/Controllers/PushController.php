<?php

namespace App\Http\Controllers;

use packages\UseCase\Task\Send\TaskSendUseCaseInterface;

class PushController extends Controller
{
    /**
     * @param TaskSendUseCaseInterface $interactor
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(TaskSendUseCaseInterface $interactor)
    {
        $interactor->handle();

        return response()->json(['ok']);
    }
}
