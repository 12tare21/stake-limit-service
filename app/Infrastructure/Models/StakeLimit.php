<?php

namespace App\Infrastructure\Models;

use App\Infrastructure\traits\Uuid4;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Model;

class StakeLimit extends Model
{
    use Uuid4;

    protected $table = 'stake_limits';

    protected $fillable = [
        'id',
        'deviceId',
        'validFrom',
        'validTo',
        'stakeLimit',
        'hotPercentage',
    ];

    public function device(){
        return $this->belongsTo(Device::class);
    }
}
