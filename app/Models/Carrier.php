<?php


namespace App\Models;


final class Carrier extends AbstractShip
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
}