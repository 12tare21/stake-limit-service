<?php

namespace App\Infrastructure\Repositories\Eloquent;

use App\Infrastructure\Repositories\Interfaces\StakeLimits;
use App\Infrastructure\Models\StakeLimit;

class StakeLimitRepository extends MutableRepository implements StakeLimits{
    public function __construct(StakeLimit $model){
        parent::__construct($model);
    }
}