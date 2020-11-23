<?php

namespace App\Infrastructure\DTO;

use Ramsey\Uuid\Uuid;

class TicketDto implements Dto{
    private $id;
    private $deviceId;
    private $stake;

    public function setId(string $id): TicketDto{
        $this->id = Uuid::fromString($id);
        return $this;
    }

    public function setDeviceId(string $deviceId): TicketDto{
        $this->deviceId = Uuid::fromString($deviceId);
        return $this;
    }

    public function setStake(float $stake): TicketDto{
        $this->stake = $stake;
        return $this;
    }

    public function toArray(){
        return [
            'id' => $this->id,
            'deviceId' => $this->deviceId,
            'stake' => $this->stake,
        ];
    }
}