<?php


namespace App\Services;


use App\Contracts\ShipInterface;
use App\Models\Battleship;
use App\Models\Carrier;
use App\Models\Cruiser;
use App\Models\Destroyer;
use App\Models\Submarine;
use TestCase;

class BattleshipServiceTest extends TestCase
{
    /**
     * @var BattleshipService
     */
    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new BattleshipService();
    }

    public function testGameIsNotReadyInitially()
    {
        $this->assertFalse($this->service->isReadyToPlay());
    }

    public function testGameIsReadyWhenAllShipsAreInPlace()
    {
        $this->service->placeShip(new Battleship(1, 1, ShipInterface::DIRECTION_RIGHT));
        $this->service->placeShip(new Carrier(2, 1, ShipInterface::DIRECTION_RIGHT));
        $this->service->placeShip(new Cruiser(3, 1, ShipInterface::DIRECTION_RIGHT));
        $this->service->placeShip(new Destroyer(4, 1, ShipInterface::DIRECTION_RIGHT));
        $this->service->placeShip(new Submarine(5, 1, ShipInterface::DIRECTION_RIGHT));

        $this->assertTrue($this->service->isReadyToPlay());
    }

    public function testShipPlacement()
    {
        $this->assertFalse($this->service->isPossibleToPlaceShip(new Battleship(10, 10, ShipInterface::DIRECTION_DOWN)));
        $this->assertFalse($this->service->isPossibleToPlaceShip(new Cruiser(10, 10, ShipInterface::DIRECTION_RIGHT)));

        $this->assertTrue($this->service->isPossibleToPlaceShip(new Battleship(1, 1, ShipInterface::DIRECTION_DOWN)));
        $this->assertTrue($this->service->isPossibleToPlaceShip(new Cruiser(10, 1, ShipInterface::DIRECTION_RIGHT)));
    }
}