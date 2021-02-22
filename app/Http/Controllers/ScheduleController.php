<?php

namespace App\Http\Controllers;

use packages\Domain\Application\Schedule\ScheduleSendInteractor;

class ScheduleController extends Controller
{
    public function index(ScheduleSendInteractor $interactor)
    {
        $interactor->handle();

        return response()->json(['ok']);
    }
}
