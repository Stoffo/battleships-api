<?php


namespace App\Services;


use App\Contracts\ShipInterface;
use App\EnemyGrid;
use App\PlayerGrid;

class BattleshipService
{
    /**
     * @var PlayerGrid
     */
    private $playerGrid;
    /**
     * @var EnemyGrid
     */
    private $enemyGrid;

    public function __construct(PlayerGrid $playerGrid, EnemyGrid $enemyGrid)
    {
        $this->playerGrid = $playerGrid;
        $this->enemyGrid = $enemyGrid;
    }

    /**
     * @return PlayerGrid
     */
    public function getPlayerGrid(): PlayerGrid
    {
        return $this->playerGrid;
    }

    /**
     * @return EnemyGrid
     */
    public function getEnemyGrid(): EnemyGrid
    {
        return $this->enemyGrid;
    }

    public function shoot(int $x, int $y)
    {
        $shipWasHit = $this->enemyGrid->shoot(1, 2);

        #$this->playerGrid->shoot();
    }

    protected function enemyShoot()
    {
        $x = random_int(0, 10);
        $y = random_int(0, 10);
    }

    public function isGameOver(): bool
    {
        return true;
    }

    public function resetGame()
    {
        $playerGridObjectPath = storage_path('app/playergrid');
        $enemyGridObjectPath = storage_path('app/enemygrid');
        file_put_contents($enemyGridObjectPath, '');
        file_put_contents($playerGridObjectPath, '');
    }

    public function __destruct()
    {
        //TODO: abstract this in a nicer way
        $playerGridObjectPath = storage_path('app/playergrid');
        $enemyGridObjectPath = storage_path('app/enemygrid');

        $serializedEnemyGrid = serialize($this->enemyGrid);
        $serializedPlayerGrid = serialize($this->playerGrid);

        file_put_contents($enemyGridObjectPath, $serializedEnemyGrid);
        file_put_contents($playerGridObjectPath, $serializedPlayerGrid);
    }
}