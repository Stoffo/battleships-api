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

    public function __construct(int $x, int $y, string $direction)
    {
        Assert::range($x, 1, 10);
        Assert::range($y, 1, 10);
        Assert::oneOf($direction, ['down', 'right']);

        $this->x = $x;
        $this->y = $y;
        $this->direction = $direction;
    }

    public function getName(): string
    {
        return strtolower(__CLASS__);
    }
}