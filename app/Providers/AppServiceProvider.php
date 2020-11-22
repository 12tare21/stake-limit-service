<?php

namespace App\Providers;

use App\Infrastructure\Repositories\Eloquent\MutableRepository;
use App\Infrastructure\Repositories\Interfaces\IRepository;
use App\Services\Implementations\StakeLimitService;
use App\Services\Interfaces\IStakeLimitService;
use Illuminate\Support\ServiceProvider;
use App\Http\Validators\ValidatorExtender;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(IRepository::class, MutableRepository::class);
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
