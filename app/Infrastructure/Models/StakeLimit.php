<?php

namespace App\Infrastructure\Models;

use App\Infrastructure\traits\Uuid4;
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
        'blockValue',
        'hotValue',
        'expiresFor',
        'expiresAt',
    ];

    public function device(){
        return $this->belongsTo(Device::class);
    }

    public function expired(){
        return $this->expiresAt && \Carbon\Carbon::now()->greaterThan($this->expiresAt);
    }

    public function stillValid(){
        return \Carbon\Carbon::now()->lessThanOrEqualTo($this->validTo);
    }
}
