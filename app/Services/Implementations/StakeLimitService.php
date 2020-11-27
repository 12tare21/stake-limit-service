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
        StakeLimit $config,
        Tickets $tickets
    ){
        $this->tickets = $tickets;
        $this->config = $config;
    }

    public function recieveTicketMessage(RecieveTicketRequest $request){
        $requestData = $request->validated();
        $timeDuration = $this->config->get('timeDuration');
        $sumFilters = [
            ['deviceId', $requestData['deviceId']]
        ];
        if($timeDuration){
            $sumFilters[] = [
                'created_at' , '>', \Carbon\Carbon::now(config('app.timezone'))->addSeconds(-$timeDuration)
            ];
        }
        $stakeSum = $this->stakeSumByDevice($requestData['deviceId']);
        
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
        $stakeLimitDto->setTimeDurationInSeconds($requestData['timeDuration'])
            ->setExpiresForInSeconds($requestData['restrictionExpires'])
            ->setBlockValue($requestData['stakeLimit'])
            ->setHotValueFromLimit($requestData['stakeLimit'], $requestData['hotPercentage']);

        return $this->config->flushConfigAndExpired()
            ->put($stakeLimitDto->toArray())
            ->stakeLimitConf();    
    }

    private function isDeviceBlocked($stakeSum, $deviceId){
        $stakeLimit = $this->config->all();
        if (!$stakeLimit)
            return false;

        $now = \Carbon\Carbon::now(config('app.timezone'))->getTimestamp();
        $blockValue = $stakeLimit['blockValue'];
        $expiresFor = $stakeLimit['expiresFor'];

        $deviceExpiry = isset($stakeLimit[$deviceId])
            ? \Carbon\Carbon::createFromTimeString($stakeLimit[$deviceId])->addSeconds($expiresFor)
            : null;

        return (!$deviceExpiry || $now < strtotime($deviceExpiry) || !$expiresFor) 
        && $stakeSum > $blockValue;
    }
    
<<<<<<< HEAD
    private function resolveDeviceStatus($stakeSum, $deviceId){
=======
    public function resolveDeviceStatus($stakeSum, $deviceId){
        $now = \Carbon\Carbon::now();
>>>>>>> feat(unit tests): temp commit, resolve device test added
        $stakeLimit = $this->config->all();
        if(!$stakeLimit){
            return DeviceStatus::OK;
        }

        $deviceExpiry = $stakeLimit[$deviceId] ?? null;
        if($this->isDeviceBlocked($stakeSum, $deviceId)){
            if(!$deviceExpiry || !$stakeLimit['expiresFor']){
                $this->config->put([
                    $deviceId => ($stakeLimit['expiresFor']
                        ? \Carbon\Carbon::now(config('app.timezone'))
                        : null)
                ]);
            }
            return DeviceStatus::BLOCKED;
        }

        if ($stakeSum > $stakeLimit['hotValue']){
            return DeviceStatus::HOT;
        }

        return DeviceStatus::OK;
    }

    public function stakeSumByDevice($deviceId){
        $sumFilters = [
            ['deviceId', $deviceId]
        ];
        $timeDuration = $this->config->get('timeDuration');
        if($timeDuration){
            $sumFilters[] = [
                'created_at' , '>', \Carbon\Carbon::now(config('app.timezone'))->addSeconds(-$timeDuration)
            ];
        }
        return $this->tickets->sumByFilters($sumFilters, 'stake');
    }
}