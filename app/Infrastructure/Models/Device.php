<?php

namespace App\Infrastructure\Models;

use App\Events\DeviceBlockedEvent;
use App\Infrastructure\Enums\DeviceStatus;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $table = 'devices';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
    ];

    protected $appends = [
        'stakeSum',
        'status'
    ];

    public function stakeLimits(){
        return $this->hasMany(StakeLimit::class, 'deviceId', 'id');
    } 

    public function tickets(){
        return $this->hasMany(Ticket::class, 'deviceId', 'id');
    } 

    public function getStakeSumAttribute(){
        return $this->stakesSum();
    }

    public function getStatusAttribute(){
        $status = DeviceStatus::OK;
        $stakeSum = $this->stakesSum();
        $this->stakeLimits->each(function ($limit) use (&$status, $stakeSum){
            if($limit->stillValid() && !$limit->expired()){
                if ($stakeSum > $limit->blockValue){
                    $status = DeviceStatus::BLOCKED;
                    event(new DeviceBlockedEvent($this->id));
                    return true;
                } else if ($stakeSum > $limit->hotValue){
                    $status = DeviceStatus::HOT;
                }
            }
        });
        return $status;
    }

    private function stakesSum(){
        return $this->tickets->sum(function($ticket){
            return $ticket->stake;
        });
    }
}
