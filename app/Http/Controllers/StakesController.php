<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConfigureStakeLimitRequest;
use App\Http\Requests\RecieveTicketRequest;
use App\Infrastructure\Enums\DeviceStatus;
use App\Services\Interfaces\IStakeLimitService;

class StakesController extends Controller
{
    public function __construct(IStakeLimitService $stakeLimitService)
    {
        $this->stakeLimitService = $stakeLimitService;
    }

    public function recieveTicketMessage(RecieveTicketRequest $request){
        return response()->json(['status' => DeviceStatus::OK], 200);
    }
    
    public function configureStakeLimit(ConfigureStakeLimitRequest $request){
        return response()->json(['status' => DeviceStatus::OK], 200);
    }
}
