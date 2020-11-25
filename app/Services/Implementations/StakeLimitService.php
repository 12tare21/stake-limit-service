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
        $stakeSum = $this->tickets
            ->findByAttribute('deviceId', $requestData['deviceId'])
            ->sum(function($ticket){
                return $ticket->stake;
            });

        $status = $this->resolveDeviceStatus($stakeSum, $requestData['deviceId']);
        if($status !== DeviceStatus::BLOCKED){
            $ticketDto = new TicketDto();
            $ticketDto
                ->setId($requestData['id'])
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
        $status = DeviceStatus::OK;
        
        //DODATI PODRSKU DA MOZEMO VISE STAKE LIMITA ZADAT PO DEVICEU
        //put seconds in validation.php
        //naming convenction kod stake.limit jsona u folder,a config da se zove fajl, a history napravit i da ide laga
        
        if(
            $now->lessThan($stakeLimit['validTo'])
            && (
                isset($stakeLimit[$deviceId]) 
                && isset($stakeLimit[$deviceId]) 
                && $now->lessThan($stakeLimit[$deviceId])
            )
            || !isset($stakeLimit[$deviceId])
        ){
            if(
                $stakeSum > $stakeLimit['blockValue']
                && !isset($stakeLimit[$deviceId])
                || $stakeLimit['expiresFor'] == 0){
                if(!isset($stakeLimit[$deviceId])){
                    $this->config->put([
                        $deviceId => \Carbon\Carbon::now()->addSeconds($stakeLimit['expiresFor'])
                    ]);
                }
                // $this->config->put(['expiresAt' => \Carbon\Carbon::now()->addSeconds($stakeLimit['expiresFor'])]);
                $status = DeviceStatus::BLOCKED;
            } else if($stakeSum > $stakeLimit['hotValue']){
                $status = DeviceStatus::HOT;
            }
        }
        
        return $status;
    }
}