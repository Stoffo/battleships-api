<?php


namespace App\Services;


use App\Contracts\ShipInterface;

class BattleshipService
{
    const GRID_SIZE = 10;

    const MAX_SUM_SHIPS = 5;

    protected $grid = [];
    private $ships = [];

    public function __construct()
    {
        $this->setUpGrid();
    }

    private function setUpGrid(): void
    {
        for ($x = 1; $x <= self::GRID_SIZE; $x++) {
            for ($y = 1; $y <= self::GRID_SIZE; $y++) {
                $this->grid[$x][$y] = null;
            }
        }
    }

    public function isReadyToPlay(): bool
    {
        return count($this->ships) === self::MAX_SUM_SHIPS;
    }

    public function shoot(int $x, int $y): bool
    {
        $ship = $this->getShipForPosition($x, $y);

        return $ship instanceof ShipInterface;
    }

    protected function getShipForPosition(int $x, int $y): ?ShipInterface
    {
        $shipNameOnCell = $this->grid[$x][$y];

        if ($shipNameOnCell) {
            return $this->ships[$shipNameOnCell];
        }

        return null;
    }

    public function isShipAlreadyPlaced(ShipInterface $ship): bool
    {
        return array_key_exists($ship->getName(), $this->ships);
    }

    public function isGameOver(): bool
    {
        //TODO: Implement Logic
        return false;
    }

    public function placeShip(ShipInterface $ship)
    {
        if ($this->isReadyToPlay() || !$this->isPossibleToPlaceShip($ship)) {
            return;
        }

        $x = $ship->getX();
        $y = $ship->getY();

        if ($ship->isHorizontal()) {
            //Put ship name in Y matrix
            for ($i = $y; $i <= $ship->getLength() + $y - 1; $i++) {
                $this->grid[$x][$i] = $ship->getName();
            }
        }

        if ($ship->isVertical()) {
            //Put ship name in X matrix
            for ($i = $y; $i <= $ship->getLength() + $y - 1; $i++) {
                $this->grid[$i][$y] = $ship->getName();
            }
        }

        $this->ships[$ship->getName()] = $ship;
    }

    /**
     * @return array
     */
    public function getGrid(): array
    {
        return $this->grid;
    }

    public function isPossibleToPlaceShip(ShipInterface $ship): bool
    {
        if (!$this->isWithinGrid($ship) || $this->isShipAlreadyPlaced($ship)) {
            return false;
        }

        if ($this->getShipForPosition($ship->getX(), $ship->getY())) {
            return false;
        }

        for ($i = 0; $i < $ship->getLength(); $i++) {
            if ($ship->isHorizontal()) {
                $x = $ship->getX();
                $y = $ship->getY() + $i;
            } else {
                $x = $ship->getX() + $i;
                $y = $ship->getY();
            }

            $shipOnCell = $this->getShipForPosition($x, $y);

            if ($shipOnCell) {
                return false;
            }
        }


        return true;
    }

    protected function isWithinGrid(ShipInterface $ship): bool
    {
        if ($ship->isVertical()) {
            return $ship->getX() + $ship->getLength() <= self::GRID_SIZE;
        }

        return $ship->getY() + $ship->getLength() <= self::GRID_SIZE;
    }
}