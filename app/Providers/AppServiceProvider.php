<?php

namespace App\Providers;

use App\EnemyGridFactory;
use App\Grid;
use App\PlayerGrid;
use App\Services\BattleshipService;
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
        $this->app->singleton(BattleshipService::class, function () {
            $playerGridObjectPath = storage_path('app/playergrid');
            $enemyGridObjectPath = storage_path('app/enemygrid');

            $playerGrid = false;
            $enemyGrid = false;
            if (file_exists($playerGridObjectPath) || file_exists($enemyGridObjectPath)) {
                $playerGrid = unserialize(file_get_contents($playerGridObjectPath));
                $enemyGrid = unserialize(file_get_contents($enemyGridObjectPath));
            }

            $playerGrid = $playerGrid instanceof Grid ? $playerGrid : new PlayerGrid();
            $enemyGrid = $enemyGrid instanceof Grid ? $enemyGrid : EnemyGridFactory::create();

            #$serialized = serialize(EnemyGridFactory::create());
            #file_put_contents($enemyGridObjectPath, $serialized);

            return new BattleshipService($playerGrid, $enemyGrid);
        });
    }
}
