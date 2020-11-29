<?php

namespace App\Services\Interfaces;

use Illuminate\Http\Request;

interface IStakeLimitService{
    function recieveTicketMessage(Request $request);
    function configureStakeLimit(Request $request);
}