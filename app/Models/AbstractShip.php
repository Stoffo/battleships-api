<?php


namespace App\Models;


use App\Contracts\ShipInterface;
use Webmozart\Assert\Assert;

abstract class AbstractShip implements ShipInterface
{
    /**
     * @var int
     */
    protected $x;
    /**
     * @var int
     */
    protected $y;
    /**
     * @var string
     */
    protected $direction;

    /**
     * @var int
     */
    protected $damage = 0;

    public function __construct(int $x, int $y, string $direction)
    {
        Assert::range($x, 1, 10);
        Assert::range($y, 1, 10);
        Assert::oneOf($direction, ['down', 'right']);

        $this->x = $x;
        $this->y = $y;
        $this->direction = $direction;
    }

    public function isHorizontal(): bool
    {
        return static::getDirection() === ShipInterface::DIRECTION_RIGHT;
    }

    public function isVertical(): bool
    {
        return static::getDirection() === ShipInterface::DIRECTION_DOWN;
    }

    public function increaseDamage(): void
    {
        if (!$this->hasSunk()) {
            $this->damage++;
        }
    }

    public function hasSunk(): bool
    {
        return $this->damage === static::getLength();
    }
}
