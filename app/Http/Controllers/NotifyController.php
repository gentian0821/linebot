<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use packages\Domain\Application\Notify\NotifySendInteractor;

class NotifyController extends Controller
{
    public function index(Request $request, NotifySendInteractor $interactor)
    {
        $interactor->handle($request->input());

        return response()->json(['ok']);
    }
}
