<?php

namespace App\Providers;

use App\Repositories\AccountRepository;
use App\Repositories\Impl\Eloquent\AccountRepositoryEloquent;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            AccountRepository::class,
            AccountRepositoryEloquent::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
