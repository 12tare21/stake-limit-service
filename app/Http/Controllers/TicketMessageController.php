<?php

namespace App\Http\Controllers;

use App\Infrastructure\Enums\DeviceStatus;
use App\Infrastructure\Models\Device;

class TicketMessageController extends Controller
{
    public function hello(){
        dd(new Device());
        return response()->json(['status' => DeviceStatus::OK], 200);
    }
}
