<?php


namespace App\Models;


use App\Contracts\ShipInterface;

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
        $this->x = $x;
        $this->y = $y;
        $this->direction = $direction;
    }

    public function getName(): string
    {
        return strtolower(__CLASS__);
    }
}