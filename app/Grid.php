<?php


namespace App;


use App\Contracts\ShipInterface;

class Grid
{
    const GRID_SIZE = 10;

    const MAX_SUM_SHIPS = 5;

    protected $grid = [];
    /**
     * @var ShipInterface[]
     */
    protected $ships = [];

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

    /**
     * @return ShipInterface[]
     */
    public function getShips(): array
    {
        return $this->ships;
    }

    public function isReadyToPlay(): bool
    {
        return count($this->ships) === self::MAX_SUM_SHIPS;
    }

    public function shoot(int $x, int $y): ?ShipInterface
    {
        $ship = $this->getShipForPosition($x, $y);

        if ($ship instanceof ShipInterface) {
            $ship->increaseDamage();

            return $ship;
        }

        return null;
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

    public function placeShip(ShipInterface $ship): bool
    {
        if (!$this->isPossibleToPlaceShip($ship)) {
            return false;
        }

        $x = $ship->getX();
        $y = $ship->getY();

        //Put ship name in Y matrix
        for ($i = $y; $i <= $ship->getLength() + $y - 1; $i++) {
            if ($ship->isHorizontal()) {
                $this->grid[$x][$i] = $ship->getName();
            } else {
                $this->grid[$i][$y] = $ship->getName();
            }
        }

        $this->ships[$ship->getName()] = $ship;

        return true;
    }

    public function getGridAsArray(): array
    {
        return $this->grid;
    }

    public function isPossibleToPlaceShip(ShipInterface $ship): bool
    {
        if ($this->isReadyToPlay()) {
            return false;
        }

        if (!$this->isWithinGrid($ship) || $this->isShipAlreadyPlaced($ship)) {
            return false;
        }

        if ($this->getShipForPosition($ship->getX(), $ship->getY()) instanceof ShipInterface) {
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

    public function allShipsAreSunk()
    {
        if (!$this->isReadyToPlay()) {
            return false;
        }

        foreach ($this->getShips() as $ship) {
            if (!$ship->hasSunk()) {
                return false;
            }
        }

        return true;
    }
}
