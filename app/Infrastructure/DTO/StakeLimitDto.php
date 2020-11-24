<?php

namespace App\Infrastructure\DTO;

class StakeLimitDto implements Dto{
    private $id;
    private $deviceId;
    private $validFrom;
    private $validTo;
    private $blockValue;
    private $hotValue;
    private $expiresFor;

    public function setId(string $id): StakeLimitDto{
        $this->id = $id;
        return $this;
    }

    public function setDeviceId(string $deviceId): StakeLimitDto{
        $this->deviceId = $deviceId;
        return $this;
    }

    public function setValidFrom(\Carbon\Carbon $validFrom): StakeLimitDto{
        $this->validFrom = $validFrom;
        return $this;
    }

    public function setValidFromInSeconds(int $duration): StakeLimitDto{
        $this->validFrom = \Carbon\Carbon::now()->addSeconds($duration);
        return $this;
    }

    public function setValidToInSeconds(int $duration): StakeLimitDto{
        $this->validTo = \Carbon\Carbon::now()->addSeconds($duration);
        return $this;
    }

    public function setValidTo(\Carbon\Carbon $validTo): StakeLimitDto{
        $this->validTo = $validTo;
        return $this;
    }

    public function setExpiresForInSeconds(int $duration): StakeLimitDto{
        $this->expiresFor = $duration;
        return $this;
    }

    public function setBlockValue(float $blockValue): StakeLimitDto{
        $this->blockValue = $blockValue;
        return $this;
    }

    public function setHotValueFromLimit(float $limit, int $hotPercentage): StakeLimitDto{
        $this->hotValue = $hotPercentage / 100 * $limit;
        return $this;
    }

    public function setHotValue(float $hotValue): StakeLimitDto{
        $this->hotValue = $hotValue;
        return $this;
    }

    public function toArray(){
        return [
            'id' => $this->id,
            'deviceId' => $this->deviceId,
            'validFrom' => $this->validFrom,
            'validTo' => $this->validTo,
            'blockValue' => $this->blockValue,
            'hotValue' => $this->hotValue,
            'expiresFor' => $this->expiresFor,
        ];
    }
}