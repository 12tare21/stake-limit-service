<?php

namespace Tests;

use App\Services\Utills\StakeLimit;
use Illuminate\Contracts\Console\Kernel;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        $app->bind(IStakeLimitService::class, StakeLimitService::class);
        $app->bind(Tickets::class, TicketRepository::class);
        $app->singleton(StakeLimit::class, function(){
            if(!file_exists('tests/data')){
                mkdir('tests/data');
            }
            return StakeLimit::make('tests/data/test-config.json');
        });
        
        return $app;
    }
}
