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
    /**
     * @var bool
     */
    private $enemyDidHitLastTime = false;
    /**
     * @var array
     */
    private $lastEnemyShotCoords = [];

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
        //Shoot the Enemy's grid
        $enemyShip = $this->enemyGrid->shoot($x, $y);
        $playerHit = $enemyShip instanceof ShipInterface;

        //Let the computer shoot at the players grid
        list($x, $y) = $this->getEnemyShot();
        $playerShip = $this->playerGrid->shoot($x, $y);

        $enemyHit = $playerShip instanceof ShipInterface;

        $this->enemyDidHitLastTime = $enemyHit;

        return [
            'player' => [
                'hit' => $playerHit,
                'sunk' => $playerHit && $enemyShip->hasSunk(),
            ],
            'enemy' => [
                'hit' => $enemyHit,
                'sunk' => $enemyHit && $playerShip->hasSunk(),
                'x' => $x,
                'y' => $y
            ]
        ];
    }

    protected function getEnemyShot()
    {
        if ($this->enemyDidHitLastTime) {
            $lastCoords = $this->lastEnemyShotCoords;
            return $lastCoords[0]--;
        }

        $x = random_int(1, 10);
        $y = random_int(1, 10);

        $this->lastEnemyShotCoords = [$x, $y];

        return [$x, $y];
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