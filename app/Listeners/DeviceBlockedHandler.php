<?php

namespace App\Listeners;

use App\Events\DeviceBlockedEvent;
use App\Infrastructure\Repositories\Interfaces\Devices;
use App\Infrastructure\Repositories\Interfaces\StakeLimits;
use Ramsey\Uuid\Uuid;

class DeviceBlockedHandler
{
    public function __construct(
        StakeLimits $stakeLimits,
        Devices $devices
    ){
        $this->stakeLimits = $stakeLimits;
        $this->devices = $devices;
    }

    public function handle(DeviceBlockedEvent $event)
    {
        $device = $this->devices->find($event->id);
        $device->stakeLimits->each(function ($limit){
            if($limit->expiresFor){
                $expiresAt = \Carbon\Carbon::now()->addSeconds($limit->expiresFor);
                $this->stakeLimits->update($limit->id, ['expiresAt' => $expiresAt]);
            }
        });
    }
}
