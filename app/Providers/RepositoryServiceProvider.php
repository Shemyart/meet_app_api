<?php

namespace App\Providers;

use App\Repositories\Eloquent\InterestRepository;
use App\Repositories\Eloquent\PersonRepository;
use App\Repositories\Interfaces\InterestRepositoryInterface;
use App\Repositories\Interfaces\PersonRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            PersonRepositoryInterface::class,
            PersonRepository::class
        );
        $this->app->bind(
            InterestRepositoryInterface::class,
            InterestRepository::class
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
