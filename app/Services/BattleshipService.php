<?php


namespace App\Services;


use App\Contracts\ShipInterface;
use App\Exceptions\GameNotReadyToPlayException;
use App\Grid;
use App\Models\Battleship;
use App\Models\Carrier;
use App\Models\Cruiser;
use App\Models\Destroyer;
use App\Models\Submarine;
use App\StateManager;

class BattleshipService
{
    /**
     * @var Grid
     */
    private $playerGrid;
    /**
     * @var Grid
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
    /**
     * @var StateManager
     */
    private $stateManager;

    public function __construct(StateManager $stateManager)
    {
        $this->stateManager = $stateManager;
        $this->playerGrid = $stateManager->getPlayerGrid();
        $this->enemyGrid = $stateManager->getEnemyGrid();
    }

    public function isReadyToPlay()
    {
        return $this->getPlayerGrid()->isReadyToPlay();
    }

    public function getPlayerGrid(): Grid
    {
        return $this->playerGrid;
    }

    public function getEnemyGrid(): Grid
    {
        return $this->enemyGrid;
    }

    public function getShipModelByName(string $name): ?string
    {
        $ships = [
            'destroyer' => Destroyer::class,
            'submarine' => Submarine::class,
            'cruiser' => Cruiser::class,
            'battleship' => Battleship::class,
            'carrier' => Carrier::class,
        ];

        return $ships[$name];
    }

    /**
     * @param int $x
     * @param int $y
     * @return array
     * @throws GameNotReadyToPlayException
     */
    public function shoot(int $x, int $y)
    {
        if (!$this->getPlayerGrid()->isReadyToPlay()) {
            throw new GameNotReadyToPlayException();
        }

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
                'lost_game' => $this->playerGrid->allShipsAreSunk()
            ],
            'enemy' => [
                'hit' => $enemyHit,
                'sunk' => $enemyHit && $playerShip->hasSunk(),
                'lost_game' => $this->enemyGrid->allShipsAreSunk(),
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

    public function resetGame()
    {
        $this->stateManager->reset();
    }

    public function __destruct()
    {
        $this->stateManager->savePlayerGrid($this->playerGrid);
        $this->stateManager->saveEnemyGrid($this->enemyGrid);
    }
}