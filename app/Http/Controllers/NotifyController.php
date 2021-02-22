<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use packages\UseCase\Notify\Send\NotifySendUseCaseInterface;

class NotifyController extends Controller
{
    public function store(Request $request, NotifySendUseCaseInterface $interactor)
    {
        $interactor->handle($request->input('message'));

        return response()->json(['ok']);
    }
}
