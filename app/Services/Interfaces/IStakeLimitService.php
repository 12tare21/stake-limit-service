<?php

namespace App\Services\Interfaces;

use App\Http\Requests\ConfigureStakeLimitRequest;
use App\Http\Requests\RecieveTicketRequest;

interface IStakeLimitService{
    function recieveTicketMessage(RecieveTicketRequest $request);
    function configureStakeLimit(ConfigureStakeLimitRequest $request);
}