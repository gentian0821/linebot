<?php

namespace App\Http\Controllers;

use packages\Domain\Application\Message\TaskSendInteractor;

class PushController extends Controller
{
    public function index(TaskSendInteractor $interactor)
    {
        $interactor->handle();

        return response()->json(['ok']);
    }
}
