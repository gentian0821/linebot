<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use packages\UseCase\Notify\Send\NotifySendUseCaseInterface;

class NotifyController extends Controller
{
    public function index(Request $request, NotifySendUseCaseInterface $interactor)
    {
        Log::info(print_r($request->getContent(), true));
        Log::info(print_r($request->input('message'), true));
        $interactor->handle($request->input('message'));

        return response()->json(['ok']);
    }
}
