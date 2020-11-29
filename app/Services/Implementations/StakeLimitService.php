<?php

namespace App\Services\Implementations;

use App\Infrastructure\DTO\StakeLimitDto;
use App\Infrastructure\DTO\TicketDto;
use App\Infrastructure\Enums\DeviceStatus;
use App\Services\Interfaces\IStakeLimitService;
use App\Infrastructure\Repositories\Interfaces\Tickets;
use App\Services\Utills\StakeLimit;
use App\Services\Utills\ValidatorExtender;
use Illuminate\Http\Request;

class StakeLimitService implements IStakeLimitService{
    public function __construct(
        StakeLimit $config,
        Tickets $tickets
    ){
        $this->tickets = $tickets;
        $this->config = $config;
    }

    public function recieveTicketMessage(Request $request){
        $requestData = ValidatorExtender::validatedIfNeeded($request);
        $stakeSum = $this->stakeSumByDevice($requestData['deviceId']);
        $status = $this->resolveDeviceStatus($stakeSum, $requestData['deviceId'], true);

        if($status !== DeviceStatus::BLOCKED){
            $ticketDto = new TicketDto();
            $ticketDto->setId($requestData['id'])
                ->setDeviceId($requestData['deviceId'])
                ->setStake($requestData['stake']);

            $stakeSum += $requestData['stake'];
            $this->tickets->create($ticketDto->toArray());
            return $this->resolveDeviceStatus($stakeSum, $requestData['deviceId'], false);
        }

        return $status;
    }

    public function configureStakeLimit(Request $request){
        $requestData = ValidatorExtender::validatedIfNeeded($request);

        $stakeLimitDto = new StakeLimitDto(); 
        $stakeLimitDto->setTimeDurationInSeconds($requestData['timeDuration'])
            ->setExpiresForInSeconds($requestData['restrictionExpires'])
            ->setBlockValue($requestData['stakeLimit'])
            ->setHotValueFromLimit($requestData['stakeLimit'], $requestData['hotPercentage']);

        return $this->config->flushConfigAndExpired()
            ->put($stakeLimitDto->toArray())
            ->stakeLimitConf();    
    }

    public function deviceBlocked($stakeSum, $deviceId, $considerExpiration){
        $stakeLimit = $this->config->all();
        if (!$stakeLimit)
            return false;

        $now = \Carbon\Carbon::now(config('app.timezone'))->getTimestamp();
        $blockValue = $stakeLimit['blockValue'];
        $expiresFor = $stakeLimit['expiresFor'];

        if(!$considerExpiration)
            return $stakeSum > $blockValue;

        $deviceExpiry = isset($stakeLimit[$deviceId])
            ? \Carbon\Carbon::createFromTimeString($stakeLimit[$deviceId])->addSeconds($expiresFor)
            : null;

        return $deviceExpiry && (!$expiresFor || $now < strtotime($deviceExpiry));
        return (!$deviceExpiry || $now < strtotime($deviceExpiry) || !$expiresFor) 
        && $stakeSum > $blockValue;
    }

    public function resolveDeviceStatus($stakeSum, $deviceId, $considerExpiration){
        $stakeLimit = $this->config->all();
        if(!$stakeLimit){
            return DeviceStatus::OK;
        }

        if($this->deviceBlocked($stakeSum, $deviceId, $considerExpiration)){
            if(!$considerExpiration){
                $this->config->put([
                    $deviceId => \Carbon\Carbon::now(config('app.timezone'))
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