<?php

namespace App\Providers;

use App\Repositories\Contracts\GenericCrudRepositoryInterface;
use App\Repositories\Eloquent\EloquentGenericCrudRepository;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            GenericCrudRepositoryInterface::class,
            EloquentGenericCrudRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();
    }
}
