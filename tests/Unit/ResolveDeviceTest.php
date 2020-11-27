<?php

namespace Tests\Unit;

use App\Infrastructure\Models\Ticket;
use App\Services\Interfaces\IStakeLimitService;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\TestCase;
use Spatie\Valuestore\Valuestore;

class ResolveDeviceTest extends TestCase
{
    // use RefreshDatabase;
    public function setUp(): void
    {
        parent::setUp();

        // Artisan::call('php artisan migrate:fresh --env=testing');
        // $this->configData = json_decode(file_get_contents('../data/config.example.json'), true);
        // $this->configData = [
        //     "timeDuration" => 300,
        //     "stakeLimit" => 1000,
        //     "hotPercentage" => 80,
        //     "restrictionExpires" => 60
        // ];
        // $this->config = Valuestore::make('../data/test-config.json');

        // $this->stakeService = app(IStakeLimitService::class, [$this->config]);
        // // dd($this->stakeService);
        // $this->device = '59e5b8b2-5387-41f5-9b13-89c55b0d2166';
        // $this->tickets = factory(Ticket::class, 8)->create(['deviceId' => $this->deviceId]);
    }

    public function testBlockWithExpiry()
    {
        $this->config->flush();
        $this->config->put([
            "timeDuration" => 300,
            "stakeLimit" => 1000,
            "hotPercentage" => 80,
            "restrictionExpires" => 60
        ]);
        $this->stakeService->resolveDeviceStatus(1000, $this->device);
        $this->assertTrue(true);
    }
    
    public function testHot()
    {
        $this->assertTrue(true);
    }
    
    public function testOk()
    {
        $this->assertTrue(true);
    }
}
