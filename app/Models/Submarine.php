<?php


namespace App\Models;


final class Submarine extends AbstractShip
{
    public function getLength(): string
    {
        return 3;
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