<?php

namespace App\Infrastructure\Enum;

use BenSampo\Enum\Enum;

final class DeviceStatus extends Enum
{
    const OK = 'OK';
    const HOT = 'HOT';
    const BLOCKED = 'BLOCKED';
}