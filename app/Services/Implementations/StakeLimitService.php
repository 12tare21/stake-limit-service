<?php

namespace App\Services\Implementations;

use App\Http\Requests\ConfigureStakeLimitRequest;
use App\Http\Requests\RecieveTicketRequest;
use App\Infrastructure\DTO\StakeLimitDto;
use App\Infrastructure\DTO\TicketDto;
use App\Infrastructure\Enums\DeviceStatus;
use App\Services\Interfaces\IStakeLimitService;
use App\Infrastructure\Repositories\Interfaces\StakeLimits;
use App\Infrastructure\Repositories\Interfaces\Devices;
use App\Infrastructure\Repositories\Interfaces\Tickets;

class StakeLimitService implements IStakeLimitService{
    public function __construct(
        Tickets $tickets,
        Devices $devices,
        StakeLimits $stakeLimits
    ){
        $this->tickets = $tickets;
        $this->devices = $devices;
        $this->stakeLimits = $stakeLimits;
    }

    public function recieveTicketMessage(RecieveTicketRequest $request){
        $requestData = $request->validated();
        $device = $this->devices->findOrCreate($requestData['deviceId'], ['id' => $requestData['deviceId']]);

        if($device->status !== DeviceStatus::BLOCKED){
            $ticketDto = new TicketDto();
            $ticketDto->setId($requestData['id'])
                ->setDeviceId($requestData['deviceId'])
                ->setStake($requestData['stake']);

            $this->tickets->create($ticketDto->toArray());
        }
        return $device->status;
    }

    public function configureStakeLimit(ConfigureStakeLimitRequest $request){
        $requestData = $request->validated();
        
        $stakeLimitDto = new StakeLimitDto();
        $stakeLimitDto->setDeviceId($requestData['deviceId'])
            ->setValidFrom(\Carbon\Carbon::now())
            ->setValidToInSeconds($requestData['timeDuration'])
            ->setExpiresForInSeconds($requestData['restrictionExpires'])
            ->setBlockValue($requestData['stakeLimit'])
            ->setHotValueFromLimit($requestData['stakeLimit'], $requestData['hotPercentage']);

        $this->devices->findOrCreate($requestData['deviceId'], ['id' => $requestData['deviceId']]);
        return $this->stakeLimits->create($stakeLimitDto->toArray());
    }
}