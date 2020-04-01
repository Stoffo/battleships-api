<?php


namespace App\Models;


use App\Contracts\ShipInterface;

final class Carrier extends AbstractShip implements ShipInterface
{
    public function getLength(): int
    {
        return 5;
    }

    public function getX(): int
    {
        return $this->x;
    }

    public function getY(): int
    {
        return $this->y;
    }

    public function getDirection(): string
    {
        return $this->direction;
    }

    public function getName(): string
    {
        return 'carrier';
    }
}