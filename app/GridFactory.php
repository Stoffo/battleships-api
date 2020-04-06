<?php


namespace App;


use App\Contracts\ShipInterface;
use App\Models\Battleship;
use App\Models\Carrier;
use App\Models\Cruiser;
use App\Models\Destroyer;
use App\Models\Submarine;
use Faker\Factory;
use LogicException;

class GridFactory
{
    private static $ships = [
        Battleship::class,
        Carrier::class,
        Cruiser::class,
        Destroyer::class,
        Submarine::class,
    ];

    public static function create(): Grid
    {
        $grid = new Grid();

        foreach (self::$ships as $shipType) {
            $isPossible = false;

            //Loop through random ship combinations until every ship has its place
            while ($isPossible === false) {
                $randomShip = self::getRandomship($shipType);

                try {
                    $isPossible = $grid->placeShip($randomShip);
                } catch (Exceptions\InvalidShipPositionException $e) {
                    $isPossible = false;
                }
            }
        }

        if (!$grid->isReadyToPlay()) {
            throw new LogicException('Game is not ready. Something went wrong!');
        }
        
        return $grid;
    }

    private static function getRandomShip($className): ShipInterface
    {
        $faker = Factory::create();

        return new $className(
            $faker->numberBetween(1, 10),
            $faker->numberBetween(1, 10),
            $faker->randomElement([ShipInterface::DIRECTION_DOWN, ShipInterface::DIRECTION_RIGHT])
        );
    }
}