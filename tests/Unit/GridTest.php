<?php


namespace App\Services;


use App\Contracts\ShipInterface;
use App\Grid;
use App\Models\Battleship;
use App\Models\Carrier;
use App\Models\Cruiser;
use App\Models\Destroyer;
use App\Models\Submarine;
use TestCase;

class GridTest extends TestCase
{
    /**
     * @var Grid
     */
    private $grid;

    public function setUp(): void
    {
        parent::setUp();

        $this->grid = new Grid();
    }

    public function testGameIsNotReadyInitially()
    {
        $this->assertFalse($this->grid->isReadyToPlay());
    }

    public function testGameIsReadyWhenAllShipsAreInPlace()
    {
        $this->grid->placeShip(new Battleship(1, 1, ShipInterface::DIRECTION_RIGHT));
        $this->grid->placeShip(new Carrier(2, 1, ShipInterface::DIRECTION_RIGHT));
        $this->grid->placeShip(new Cruiser(3, 1, ShipInterface::DIRECTION_RIGHT));
        $this->grid->placeShip(new Destroyer(4, 1, ShipInterface::DIRECTION_RIGHT));
        $this->grid->placeShip(new Submarine(5, 1, ShipInterface::DIRECTION_RIGHT));

        $this->assertTrue($this->grid->isReadyToPlay());
    }

    public function testShipPlacement()
    {
        $this->assertFalse($this->grid->isPossibleToPlaceShip(new Battleship(10, 10, ShipInterface::DIRECTION_DOWN)));
        $this->assertFalse($this->grid->isPossibleToPlaceShip(new Cruiser(10, 10, ShipInterface::DIRECTION_RIGHT)));

        $this->assertTrue($this->grid->isPossibleToPlaceShip(new Battleship(1, 1, ShipInterface::DIRECTION_DOWN)));
        $this->assertTrue($this->grid->isPossibleToPlaceShip(new Cruiser(10, 1, ShipInterface::DIRECTION_RIGHT)));
    }

    public function testShipPlacementWhenShipIsInTheWayHorizontal()
    {
        $this->grid->placeShip(new Battleship(1, 3, ShipInterface::DIRECTION_RIGHT));

        $this->assertFalse($this->grid->isPossibleToPlaceShip(new Cruiser(1, 1, ShipInterface::DIRECTION_RIGHT)));
    }

    public function testShipPlacementWhenShipIsInTheWayVertical()
    {
        $this->grid->placeShip(new Battleship(3, 3, ShipInterface::DIRECTION_DOWN));

        $this->assertTrue($this->grid->isPossibleToPlaceShip(new Destroyer(1, 3, ShipInterface::DIRECTION_DOWN)));
        $this->assertFalse($this->grid->isPossibleToPlaceShip(new Cruiser(1, 3, ShipInterface::DIRECTION_DOWN)));
    }

    public function testAlreadyPlaced()
    {
        $this->grid->placeShip(new Battleship(3, 3, ShipInterface::DIRECTION_DOWN));
        $this->assertFalse($this->grid->isPossibleToPlaceShip(new Battleship(1, 3, ShipInterface::DIRECTION_DOWN)));
    }

    public function testAllShipsAreSunkInitially()
    {
        $this->grid->placeShip(new Battleship(2, 1, ShipInterface::DIRECTION_RIGHT));
        $this->grid->placeShip(new Carrier(2, 1, ShipInterface::DIRECTION_RIGHT));
        $this->grid->placeShip(new Cruiser(3, 1, ShipInterface::DIRECTION_RIGHT));
        $this->grid->placeShip(new Destroyer(4, 1, ShipInterface::DIRECTION_RIGHT));
        $this->grid->placeShip(new Submarine(5, 1, ShipInterface::DIRECTION_RIGHT));

        $this->assertFalse($this->grid->allShipsAreSunk());
    }
}