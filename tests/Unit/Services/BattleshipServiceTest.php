<?php


namespace App\Services;


use App\GridFactory;
use App\PlayerGrid;
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

        $this->service = new BattleshipService(new PlayerGrid(), GridFactory::create());
    }

    public function testGameIsNotReadyInitially()
    {
        $this->assertFalse($this->service->isReadyToPlay());
    }

    public function testGameIsReadyWhenAllShipsArePlaces()
    {
        $service = new BattleshipService(GridFactory::create(), GridFactory::create());
        $this->assertTrue($service->isReadyToPlay());
    }

    public function testShootFunctionReturnsValues()
    {
        $result = $this->service->shoot(1, 2);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('player', $result);
        $this->assertArrayHasKey('enemy', $result);
    }
}