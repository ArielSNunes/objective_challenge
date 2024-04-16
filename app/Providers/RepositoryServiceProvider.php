<?php

namespace App\Providers;

use App\Repositories\AccountRepository;
use App\Repositories\Impl\Eloquent\AccountRepositoryEloquent;
use App\Repositories\Impl\Eloquent\TransactionRepositoryEloquent;
use App\Repositories\TransactionRepository;
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

        $this->app->bind(
            TransactionRepository::class,
            TransactionRepositoryEloquent::class
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
