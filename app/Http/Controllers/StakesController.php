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

    /**
     * @OA\Post(
     *   path="/api/stakes/tickets",
     *   tags={"Ticket message"},
     *   summary="Create ticket and resolve device status",
     *   @OA\RequestBody(
     *         required=true,
     *         description="Request object to resolve ticket message",
     *         @OA\JsonContent(
     *              required={"id", "deviceId", "stake"},
     *              @OA\Property(property="id", type="string",format="uuid", description="ID of ticket message"),
     *              @OA\Property(property="deviceId", type="string",format="uuid", description="ID of device on which stake registers on"),
     *              @OA\Property(property="stake", type="number", example=400, description="Stake amount")
     *        ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Ticket resolved"
     *   )
     * )
    */
    public function recieveTicketMessage(RecieveTicketRequest $request){
        return response()->json(['status' => $this->stakeLimitService->recieveTicketMessage($request)], 200);
    }

    /**
     * @OA\Put(
     *   path="/api/stakes/config",
     *   tags={"Stake limit"},
     *   summary="Updates global stake limit configuration file.",
     *   @OA\RequestBody(
     *         required=true,
     *         description="Request object to update stake limit configuration",
    *         @OA\JsonContent(
     *              required={"timeDuration", "stakeLimit", "hotPercentage", "restrictionExpires"},
     *              @OA\Property(property="timeDuration", type="number", example=300, description="Time duration for what config is valid in seconds."),
     *              @OA\Property(property="stakeLimit", type="number", example=1000, description="Amount after which device is BLOCKED"),
     *              @OA\Property(property="hotPercentage", type="number", example=50, description="Percentage of stakeLimit for which device declares as HOT"),
     *              @OA\Property(property="restrictionExpires", type="number", example=120, description="Time period of expiration of blockade on device in seconds")
     *        ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Stake limit updated"
     *   )
     * )
    */
    public function configureStakeLimit(ConfigureStakeLimitRequest $request){
        return response()->json(['response' => $this->stakeLimitService->configureStakeLimit($request)], 200);
    }
}
