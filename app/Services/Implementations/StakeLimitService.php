<?php

namespace App\Services\Implementations;

use App\Http\Requests\ConfigureStakeLimitRequest;
use App\Http\Requests\RecieveTicketRequest;
use App\Infrastructure\DTO\StakeLimitDto;
use App\Infrastructure\DTO\TicketDto;
use App\Infrastructure\Enums\DeviceStatus;
use App\Services\Interfaces\IStakeLimitService;
use App\Infrastructure\Repositories\Interfaces\Tickets;
use App\Services\Utills\StakeLimit;

class StakeLimitService implements IStakeLimitService{
    public function __construct(
        Tickets $tickets,
        StakeLimit $config
    ){
        $this->tickets = $tickets;
        $this->config = $config;
    }

    public function recieveTicketMessage(RecieveTicketRequest $request){
        $requestData = $request->validated();
        $stakeSum = $this->tickets->sumByAttribute('stake', 'deviceId', $requestData['deviceId']);
        $status = $this->resolveDeviceStatus($stakeSum, $requestData['deviceId']);
        
        if($status !== DeviceStatus::BLOCKED){
            $ticketDto = new TicketDto();
            $ticketDto->setId($requestData['id'])
                ->setDeviceId($requestData['deviceId'])
                ->setStake($requestData['stake']);

            $stakeSum += $requestData['stake'];
            $this->tickets->create($ticketDto->toArray());
        }
    
        return $this->resolveDeviceStatus($stakeSum, $requestData['deviceId']);
    }

    public function configureStakeLimit(ConfigureStakeLimitRequest $request){
        $requestData = $request->validated();
        
        $stakeLimitDto = new StakeLimitDto();
        $stakeLimitDto->setValidToInSeconds($requestData['timeDuration'])
            ->setExpiresForInSeconds($requestData['restrictionExpires'])
            ->setBlockValue($requestData['stakeLimit'])
            ->setHotValueFromLimit($requestData['stakeLimit'], $requestData['hotPercentage']);

        $this->config->flush();
        $this->config->put($stakeLimitDto->toArray());
        return $this->config->all();    
    }
    
    private function resolveDeviceStatus($stakeSum, $deviceId){
        $now = \Carbon\Carbon::now();
        $stakeLimit = $this->config->all();
        $device = isset($stakeLimit[$deviceId]) ? $stakeLimit[$deviceId] : null;
        
        if(is_null($stakeLimit) || $now->getTimestamp() > strtotime($stakeLimit['validTo']))
            return DeviceStatus::OK;

        if(
            (!$device || $now->getTimestamp() < strtotime($device) || $stakeLimit['expiresFor'] === 0) 
            && $stakeSum > $stakeLimit['blockValue']
        ){
            if(!$device){
                $this->config->put([
                    $deviceId => $now->addSeconds($stakeLimit['expiresFor'])
                ]);
            }
            return DeviceStatus::BLOCKED;
        } else if ($stakeSum > $stakeLimit['hotValue']){
            return DeviceStatus::HOT;
        }

        return DeviceStatus::OK;
    }
}