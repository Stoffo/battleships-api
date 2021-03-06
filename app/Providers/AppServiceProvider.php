<?php

namespace App\Providers;

use App\GridFactory;
use App\Services\BattleshipService;
use App\StateManager;
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
        $this->app->singleton(StateManager::class, function () {
            return new StateManager(new GridFactory());
        });

        $this->app->singleton(BattleshipService::class, function () {
            return new BattleshipService($this->app->get(StateManager::class));
        });
    }
}
