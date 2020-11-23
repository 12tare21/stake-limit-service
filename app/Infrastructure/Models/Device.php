<?php

namespace App\Infrastructure\Models;

use App\Infrastructure\Enums\DeviceStatus;
use App\Infrastructure\traits\Uuid4;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use Uuid4;

    protected $table = 'devices';

    protected $fillable = [
        'id',
    ];

    protected $appends = [
        'stakeSum',
        'status'
    ];

    public function stakeLimits(){
        return $this->hasMany(StakeLimit::class);
    } 

    public function tickets(){
        return $this->hasMany(Ticket::class);
    } 

    public function stakeSum(){
        return $this->tickets->sum(function($ticket){
            return $ticket->stake;
        });
    }

    public function status(){
        $status = DeviceStatus::OK;
        $stakeSum = $this->stakeSum(); 
        $this->stakeLimits->each(function ($limit) use ($status, $stakeSum){
            if($limit->expired()){
                if ($stakeSum > $limit->blockValue){
                    $status = DeviceStatus::BLOCKED;
                } else if ($stakeSum > $limit->hotValue){
                    $status = DeviceStatus::HOT;
                }
            }
        });
        return $status;
    }
}
