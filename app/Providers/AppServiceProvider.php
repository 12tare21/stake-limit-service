<?php

namespace App\Providers;

use App\Infrastructure\Repositories\Eloquent\MutableRepository;
use App\Infrastructure\Repositories\Interfaces\IRepository;
use Illuminate\Support\ServiceProvider;

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
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
