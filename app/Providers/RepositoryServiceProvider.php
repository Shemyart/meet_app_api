<?php

namespace App\Providers;

use App\Repositories\Eloquent\PersonRepository;
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
