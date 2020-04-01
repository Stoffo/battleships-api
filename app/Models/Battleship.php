<?php


namespace App\Models;


final class Battleship extends AbstractShip
{
    public function getLength(): int
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

    public function getName(): string
    {
        return 'battleship';
    }
}