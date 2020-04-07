<?php


namespace App\Services;


use App\Contracts\ShipInterface;
use App\Grid;
use App\GridFactory;
use App\Models\Battleship;
use App\Models\Carrier;
use App\Models\Cruiser;
use App\Models\Destroyer;
use App\Models\Submarine;
use App\StateManager;
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

        //We do not need states here, so we mock the manager class
        $stateManagerMock = $this->prophesize(StateManager::class);
        $stateManagerMock->getPlayerGrid()->willReturn(GridFactory::create());
        $stateManagerMock->getEnemyGrid()->willReturn(GridFactory::create());

        $this->service = new BattleshipService($stateManagerMock->reveal());
    }

    public function testShootFunctionReturnsValues()
    {
        $result = $this->service->shoot(1, 2);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('player', $result);
        $this->assertArrayHasKey('enemy', $result);
    }

    public function testShoot()
    {
        $this->markTestSkipped('Prophey does not work as expected');

        $grid = new Grid();
        $grid->placeShip(new Battleship(1, 1, ShipInterface::DIRECTION_RIGHT));
        $grid->placeShip(new Carrier(10, 2, ShipInterface::DIRECTION_RIGHT));
        $grid->placeShip(new Cruiser(3, 3, ShipInterface::DIRECTION_RIGHT));
        $grid->placeShip(new Destroyer(5, 4, ShipInterface::DIRECTION_RIGHT));
        $grid->placeShip(new Submarine(6, 5, ShipInterface::DIRECTION_RIGHT));

        $stateManagerMock = $this->prophesize(StateManager::class);
        $stateManagerMock->getPlayerGrid()->shouldBeCalled()->willReturn(GridFactory::create());
        $stateManagerMock->getEnemyGrid()->shouldBeCalled()->willReturn($grid);
        $stateManagerMock->savePlayerGrid()->shouldBeCalled()->willReturn($grid);
        $stateManagerMock->saveEnemyGrid()->shouldBeCalled()->willReturn($grid);

        $service = new BattleshipService($stateManagerMock->reveal());

        $result = $service->shoot(1, 1);
        $this->assertTrue($result['player']['hit']);
        $this->assertFalse($result['player']['sunk']);

        $result = $service->shoot(1, 2);
        $this->assertTrue($result['player']['hit']);
        $this->assertFalse($result['player']['sunk']);

        $result = $service->shoot(1, 3);
        $this->assertTrue($result['player']['hit']);
        $this->assertFalse($result['player']['sunk']);

        $result = $service->shoot(1, 4);
        $this->assertTrue($result['player']['hit']);
        $this->assertTrue($result['player']['sunk']);
    }
}