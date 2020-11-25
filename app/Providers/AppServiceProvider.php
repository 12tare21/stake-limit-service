<?php

namespace App\Providers;

use App\Services\Implementations\StakeLimitService;
use App\Services\Interfaces\IStakeLimitService;
use Illuminate\Support\ServiceProvider;
use App\Infrastructure\Repositories\Eloquent\MutableRepository;
use App\Infrastructure\Repositories\Eloquent\TicketRepository;
use App\Infrastructure\Repositories\Interfaces\Repository;
use App\Infrastructure\Repositories\Interfaces\Tickets;
use App\Services\Utills\ValidatorExtender;
use App\Services\Utills\StakeLimit;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //bind singletons
        $this->app->singleton(StakeLimit::class, function(){
            return StakeLimit::make(storage_path('app/stake-limit.config.json'));
        });

        // register repositories
        $this->app->bind(Repository::class, MutableRepository::class);
        $this->app->bind(Tickets::class, TicketRepository::class);

        // register services
        $this->app->bind(IStakeLimitService::class, StakeLimitService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        ValidatorExtender::extends();
    }

}
