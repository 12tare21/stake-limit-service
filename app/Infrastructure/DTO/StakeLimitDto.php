<?php

namespace App\Infrastructure\DTO;

class StakeLimitDto implements Dto{
    private $timeDuration;
    private $blockValue;
    private $hotValue;
    private $expiresFor;

    public function setId(string $id): StakeLimitDto{
        $this->id = $id;
        return $this;
    }

    public function setTimeDurationInSeconds(int $duration): StakeLimitDto{
        $this->timeDuration = $duration;
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

    public function setExpiresAt(int $expiresFor): StakeLimitDto{
        $this->expiresAt = \Carbon\Carbon::now(config('app.timezone'))->addSeconds($expiresFor);
        return $this;
    }

    public function toArray(){
        return [
            'timeDuration' => $this->timeDuration,
            'blockValue' => $this->blockValue,
            'hotValue' => $this->hotValue,
            'expiresFor' => $this->expiresFor,
        ];
    }
}