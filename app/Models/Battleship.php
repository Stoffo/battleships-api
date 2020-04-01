<?php


namespace App\Models;


final class Battleship extends AbstractShip
{
    public function getLength(): string
    {
        return 4;
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