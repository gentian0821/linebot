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
    public function index(Request $request)
    {
        $param = $request->input();
        $device = Device::where('identification_number', $param['identification'])->first();
        $locations = Location::where('device_id', $device->device_id)->get();
        Log::info($device->device_id);
        Log::info($locations);
        $result = [];

        foreach ($locations as $location) {
            $result[] = [
                'longitude' => $location->longitude,
                'latitude' => $location->latitude,
                'measured_at' => $location->measured_at,
            ];
        }

        return response()->json($result);
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
        $location->measured_at = date('Y-m-d H:i:s');
        $location->save();

        $message = [
            [
                'type' => 'text',
                'text' => "移動してるよー\nhttps://www.google.co.jp/maps?q={$param['latitude']},{$param['longitude']}",
            ]
        ];

        $this->message_api->push($message, Config::get('const.test_send_to'));

        return response()->json(['ok']);
    }
}
