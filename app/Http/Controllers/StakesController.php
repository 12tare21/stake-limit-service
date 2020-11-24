<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConfigureStakeLimitRequest;
use App\Http\Requests\RecieveTicketRequest;
use App\Services\Interfaces\IStakeLimitService;

class StakesController extends Controller
{
    public function __construct(IStakeLimitService $stakeLimitService)
    {
        $this->stakeLimitService = $stakeLimitService;
    }

    public function recieveTicketMessage(RecieveTicketRequest $request){
        return response()->json(['status' => $this->stakeLimitService->recieveTicketMessage($request)], 200);
    }
    
    public function configureStakeLimit(ConfigureStakeLimitRequest $request){
        return response()->json(['response' => $this->stakeLimitService->configureStakeLimit($request)], 200);
    }
}
