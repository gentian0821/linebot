<?php

namespace App\Http\Controllers;

use App\Device;
use App\Location;
use App\Services\MessageApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class LocationController extends Controller
{
    private $message_api;

    public function __construct(MessageApiService $message_api)
    {
        $this->message_api = $message_api;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $param = $request->input();

        Log::info($param);

        $device = Device::where('identification_number', $param['identification'])->first();

        if (!$device) {
            $device = new Device;
            $device->identification_number = $param['identification'];
            $device->name = $param['name'];
            $device->save();
        }

        $location = new Location;
        $location->device_id = $device->id;
        $location->longitude = $param['longitude'];
        $location->latitude = $param['latitude'];
        $location->save();

        $message = [
            [
                'type' => 'text',
                'text' => "移動してるよー\nhttps://www.google.co.jp/maps/@{$param['latitude']},{$param['longitude']},15z?hl=ja",
            ]
        ];

        $this->message_api->push($message, Config::get('const.test_send_to'));

        return response()->json(['ok']);
    }
}
