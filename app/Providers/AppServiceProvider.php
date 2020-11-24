<?php

namespace App\Providers;

use App\Services\Implementations\StakeLimitService;
use App\Services\Interfaces\IStakeLimitService;
use Illuminate\Support\ServiceProvider;
use App\Http\Validators\ValidatorExtender;
use App\Infrastructure\Repositories\Eloquent\DeviceRepository;
use App\Infrastructure\Repositories\Eloquent\MutableRepository;
use App\Infrastructure\Repositories\Eloquent\StakeLimitRepository;
use App\Infrastructure\Repositories\Eloquent\TicketRepository;
use App\Infrastructure\Repositories\Interfaces\Devices;
use App\Infrastructure\Repositories\Interfaces\Repository;
use App\Infrastructure\Repositories\Interfaces\StakeLimits;
use App\Infrastructure\Repositories\Interfaces\Tickets;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // register repositories
        $this->app->bind(Repository::class, MutableRepository::class);
        $this->app->bind(Devices::class, DeviceRepository::class);    
        $this->app->bind(Tickets::class, TicketRepository::class);
        $this->app->bind(StakeLimits::class, StakeLimitRepository::class);

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
