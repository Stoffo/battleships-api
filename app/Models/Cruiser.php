<?php


namespace App\Models;


final class Cruiser extends AbstractShip
{
    public function getLength(): int
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

    public function getName(): string
    {
        return 'cruiser';
    }
}