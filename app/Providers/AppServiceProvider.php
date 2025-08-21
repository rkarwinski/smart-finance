<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Services\Contracts\SavingServiceInterface::class, \App\Services\SavingService::class);
        $this->app->singleton(\App\Services\Contracts\ExpenseServiceInterface::class, \App\Services\ExpenseService::class);
        $this->app->singleton(\App\Services\Contracts\IncomeServiceInterface::class, \App\Services\IncomeService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
