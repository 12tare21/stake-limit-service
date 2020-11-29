<?php

namespace App\Services\Utills;

use Ramsey\Uuid\Uuid;
use Spatie\Valuestore\Valuestore;

class StakeLimit extends Valuestore{
    public function hasMultiple(... $strings){
        $all = $this->all();
        foreach($strings as $key){
            if(!array_key_exists($key, $all))
                return false;
        }
        return true;
    }

    public function stakeLimitConf(){
        $all = $this->all();
        if($this->hasMultiple('timeDuration', 'blockValue', 'hotValue', 'expiresFor')){
            return [
                'timeDuration' => $all['timeDuration'],
                'blockValue' => $all['blockValue'],
                'hotValue' => $all['hotValue'],
                'expiresFor' => $all['expiresFor'],
            ];
        }
        return [];
    }

    public function expiredDevices(){
        $all = $this->all();
        $expiresFor = $all['expiresFor'] ?? 0;
        $expired = [];
        if(!$expiresFor)
            return $expired;
            
        $now = \Carbon\Carbon::now(config('app.timezone'))->getTimestamp();
        foreach($all as $key => $value){
            if(Uuid::isValid($key) && strtotime($value)){
                $deviceExpiry = \Carbon\Carbon::createFromTimeString($value)
                    ->addSeconds($expiresFor)
                    ->getTimestamp();
                if($now > $deviceExpiry){
                    $expired[$key] = $value;
                }
            }
        }
        return $expired;
    }

    public function flushConfigAndExpired(){
        return $this->setContent($this->notExpiredDevices());
    }

    public function notExpiredDevices(){
        return array_diff(
            $this->all(), 
            $this->stakeLimitConf(),
            $this->expiredDevices()
        );
    }
}