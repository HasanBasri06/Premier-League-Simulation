<?php

namespace App\Providers;

use App\Repository\TeamRepository;
use App\Repository\TeamRepositoryInterface;
use App\Service\FootballService;
use App\Service\FotballService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(TeamRepositoryInterface::class, TeamRepository::class);
        $this->app->bind(FootballService::class, function (Application $app) {
            return new FootballService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
