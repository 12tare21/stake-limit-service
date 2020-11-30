<?php

namespace Tests\Unit;

use App\Infrastructure\Enums\DeviceStatus;
use App\Infrastructure\Repositories\Interfaces\Tickets;
use App\Services\Interfaces\IStakeLimitService;
use App\Services\Utills\StakeLimit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResolveDeviceStatusTest extends TestCase
{
    use RefreshDatabase;
    public function setUp(): void
    {
        parent::setUp();
        $this->configData = json_decode(file_get_contents('tests/data/config.example.json'), true);
        if(!file_exists('tests/data')){
            mkdir('tests/data');
        }
        $this->config = StakeLimit::make('tests/data/test-config.json');
        $this->tickets = app(Tickets::class);
        $this->stakeLimitService = app(IStakeLimitService::class);
        $this->deviceId = '59e5b8b2-5387-41f5-9b13-89c55b0d2166';
    }

    public function testBlockResolveWithExpiry()
    {
        $this->config->flush()
            ->put($this->configData['withExpiry1000For60'])
            ->put([$this->deviceId => \Carbon\Carbon::now()->addSeconds(-58)]);
        $status = $this->stakeLimitService->resolveDeviceStatus(500, $this->deviceId, true);
        
        $this->assertEquals(DeviceStatus::BLOCKED, $status);
    }

    public function testBlockResolveWithoutExpiryEver()
    {
        $this->config->flush()
            ->put($this->configData['withoutExpiry1000'])
            ->put([$this->deviceId => \Carbon\Carbon::now()->addCenturies(-1)]);
        $status = $this->stakeLimitService->resolveDeviceStatus(666, $this->deviceId, true);

        $this->assertEquals(DeviceStatus::BLOCKED, $status);
    }

    public function testBlockWithBiggerSumAndExpirySet()
    {
        $this->config->flush()
            ->put($this->configData['withoutExpiry1000']);
        $status = $this->stakeLimitService->resolveDeviceStatus(1001, $this->deviceId, false);
        $deviceExpiry = \Carbon\Carbon::createFromTimeString($this->config->get($this->deviceId));
        $now = \Carbon\Carbon::now();

        $this->assertEquals(DeviceStatus::BLOCKED, $status);
        $this->assertEquals($now->toTimeString(), $deviceExpiry->toTimeString());
        $this->assertEquals($now->toDateString(), $deviceExpiry->toDateString());
    }
    
    public function testHotExpiredRestrictionStatus()
    {
        $this->config->flush()
            ->put($this->configData['expiresFor1Sec1000'])
            ->put([$this->deviceId => \Carbon\Carbon::now()->addSeconds(-50)]);

        $status = $this->stakeLimitService->resolveDeviceStatus(1200, $this->deviceId, true);
        $deviceExpiry = \Carbon\Carbon::createFromTimeString($this->config->get($this->deviceId));
        $now = \Carbon\Carbon::now();

        $this->assertEquals(DeviceStatus::HOT, $status);
        $this->assertTrue($now->getTimestamp() > $deviceExpiry->getTimestamp());
    }
    
    public function testHotNoRestriction()
    {
        $this->config->flush()
            ->put($this->configData['expiresFor1Sec1000']);
        $status = $this->stakeLimitService->resolveDeviceStatus(600, $this->deviceId, false);

        $this->assertEquals(DeviceStatus::HOT, $status);
    }
    
    public function testOkNoRestriction()
    {
        $this->config->flush()
            ->put($this->configData['expiresFor1Sec1000']);
        $status = $this->stakeLimitService->resolveDeviceStatus(500, $this->deviceId, false);
        
        $this->assertEquals(DeviceStatus::OK, $status);
    }
    
    public function testOkExpiredRestrictionStatus()
    {
        $this->config->flush()
            ->put($this->configData['expiresFor1Sec1000'])
            ->put([$this->deviceId => \Carbon\Carbon::now()->addSeconds(-50)]);

        $status = $this->stakeLimitService->resolveDeviceStatus(500, $this->deviceId, true);
        $deviceExpiry = \Carbon\Carbon::createFromTimeString($this->config->get($this->deviceId));
        $now = \Carbon\Carbon::now();

        $this->assertEquals(DeviceStatus::OK, $status);
        $this->assertTrue($now->getTimestamp() > $deviceExpiry->getTimestamp());
    }
    
    public function testOkNoStakeLimit()
    {
        $this->config->flush();
        $status = $this->stakeLimitService->resolveDeviceStatus(500, $this->deviceId, true);

        $this->assertEquals(DeviceStatus::OK, $status);
    }
}
