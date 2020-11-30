<?php

namespace Tests\Unit;

use App\Infrastructure\Models\Ticket;
use App\Infrastructure\Repositories\Interfaces\Tickets;
use App\Services\Interfaces\IStakeLimitService;
use App\Services\Utills\StakeLimit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketSumTest extends TestCase
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

    public function testTicketSumWithDuration()
    {
        factory(Ticket::class, 10)->create(['deviceId' => $this->deviceId, 'stake' => 200]);
        $ticket = factory(Ticket::class)->make(['deviceId' => $this->deviceId, 'stake' => 200]);
        $ticket->created_at = \Carbon\Carbon::now()->addCenturies(-1);
        $ticket->save();
        $this->config->flush()
            ->put($this->configData['duration300']);
        
        $stakeSum = $this->stakeLimitService->stakeSumByDevice($this->deviceId);

        $this->assertEquals(2000, $stakeSum);

        $ticket->created_at = \Carbon\Carbon::now();
        $ticket->save();
        $stakeSum = $this->stakeLimitService->stakeSumByDevice($this->deviceId);
        
        $this->assertEquals(2200, $stakeSum);
    }

    public function testTicketSumWithoutDuration(){
        factory(Ticket::class, 10)->create(['deviceId' => $this->deviceId, 'stake' => 200]);
        $stakeSum = $this->stakeLimitService->stakeSumByDevice($this->deviceId);
        
        $this->assertEquals(2000, $stakeSum);
    }
}
