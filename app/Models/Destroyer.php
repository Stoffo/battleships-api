<?php


namespace App\Models;


final class Destroyer extends AbstractShip
{
    public function getLength(): string
    {
        return 2;
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