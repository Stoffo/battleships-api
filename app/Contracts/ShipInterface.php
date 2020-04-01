<?php


namespace App\Contracts;

interface ShipInterface
{
    public function getName(): string;

    public function getLength(): string;

    public function getX(): int;

    public function getY(): int;

    public function getDirection(): string;
}