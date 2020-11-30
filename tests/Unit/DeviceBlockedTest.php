<?php

namespace Tests\Unit;

use App\Infrastructure\Repositories\Interfaces\Tickets;
use App\Services\Interfaces\IStakeLimitService;
use App\Services\Utills\StakeLimit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResolveDeviceTest extends TestCase
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

    public function testBlockWithExpiry()
    {
        $this->config->flush()
            ->put($this->configData['withExpiry1000For60'])
            ->put([$this->deviceId => \Carbon\Carbon::now()->addSeconds(-59)]);
        $blocked = $this->stakeLimitService->deviceBlocked(500, $this->deviceId, true);

        $this->assertTrue($blocked);
    }

    public function testBlockWithoutExpiry()
    {
        $this->config->flush()->put($this->configData['withoutExpiry1000']);
        $blocked = $this->stakeLimitService->deviceBlocked(1000, $this->deviceId, false);

        $this->assertTrue($blocked);
    }
    
    public function testExpiredRestriction()
    {
        $this->config->flush()
            ->put($this->configData['withExpiry1000For60'])
            ->put([$this->deviceId => \Carbon\Carbon::now()->addSeconds(-61)]);
        $blocked = $this->stakeLimitService->deviceBlocked(500, $this->deviceId, true);

        $this->assertFalse($blocked);
    }
    
    public function testNotBlocked()
    {
        $this->config->flush()
            ->put($this->configData['withExpiry1000For60']);

        $blocked = $this->stakeLimitService->deviceBlocked(999, $this->deviceId,false);

        $this->assertFalse($blocked);
    }
    
    public function testBlockNeverExpires()
    {
        $this->config->flush()
            ->put($this->configData['withoutExpiry1000'])
            ->put([$this->deviceId => \Carbon\Carbon::now()->addYears(-1)]);

        $blocked = $this->stakeLimitService->deviceBlocked(1200, $this->deviceId,true);
        
        $this->assertTrue($blocked);
    }
}
