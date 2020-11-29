<?php

namespace App\Services\Interfaces;

use Illuminate\Http\Request;

interface IStakeLimitService{
    function recieveTicketMessage(Request $request);
    function configureStakeLimit(Request $request);
    function deviceBlocked($stakeSum, $deviceId, $considerExpiration);
    function resolveDeviceStatus($stakeSum, $deviceId, $considerExpiration);
    function stakeSumByDevice($deviceId);
}