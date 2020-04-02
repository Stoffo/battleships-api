<?php


namespace Unit\Models;


use App\Contracts\ShipInterface;
use App\Models\Battleship;
use App\Models\Submarine;
use InvalidArgumentException;
use TestCase;

class AbstractShipTest extends TestCase
{
    public function testConstructor()
    {
        $this->expectException(InvalidArgumentException::class);
        new Submarine(1, 12, ShipInterface::DIRECTION_RIGHT);

        $this->expectException(InvalidArgumentException::class);
        new Submarine(21, 5, ShipInterface::DIRECTION_RIGHT);

        $this->expectException(InvalidArgumentException::class);
        new Battleship(1, 5, 'foo');
    }

    public function testGetters()
    {
        $ship = new Submarine(1, 2, ShipInterface::DIRECTION_DOWN);

        $this->assertFalse($ship->hasSunk());

        $ship->increaseDamage();
        $ship->increaseDamage();
        $ship->increaseDamage();

        $this->assertTrue($ship->hasSunk());
    }
}