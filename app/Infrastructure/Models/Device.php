<?php

namespace App\Infrastructure\Models;

use App\Infrastructure\traits\Uuid4;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use Uuid4;

    protected $table = 'devices';

    protected $fillable = [
        'id'
    ];

    protected $appends = [
        'stakeSum'
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
}
