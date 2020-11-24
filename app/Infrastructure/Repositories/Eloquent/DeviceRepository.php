<?php

namespace App\Infrastructure\Repositories\Eloquent;

use App\Infrastructure\Models\Device;
use App\Infrastructure\Repositories\Interfaces\Devices;

class DeviceRepository extends MutableRepository implements Devices{
    public function __construct(Device $model){
        parent::__construct($model);
    }
}