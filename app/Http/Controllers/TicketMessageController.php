<?php

namespace App\Http\Controllers;

use App\Infrastructure\Enums\DeviceStatus;

class TicketMessageController extends Controller
{
    public function hello(){
        return response()->json(['status' => DeviceStatus::OK], 200);
    }
}
