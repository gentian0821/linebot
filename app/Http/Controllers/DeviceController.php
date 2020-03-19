<?php

namespace App\Http\Controllers;

use App\Device;
use App\Location;
use App\Services\MessageApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class DeviceController extends Controller
{
    private $message_api;

    public function __construct(MessageApiService $message_api)
    {
        $this->message_api = $message_api;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $devices = Device::all();

        $result = [];

        foreach ($devices as $device) {
            $result[] = [
                'id' => $device->id,
                'identification' => $device->identification_number,
                'name' => $device->name,
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

        if (!$param['identification']) {
            $identification = hash_hmac('sha256', uniqid(), 'sensen_salt');
            $device = new Device;
            $device->identification_number = $identification;
            $device->name = $param['name'];
            $device->save();
        } else {
            $device = Device::where('identification_number', $param['identification'])->first();
        }

        $result = [
            'id' => $device->id,
            'identification' => $device->identification_number,
            'name' => $device->name,
        ];

        return response()->json($result);
    }
}
