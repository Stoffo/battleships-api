<?php


namespace App\Contracts;

interface ShipInterface
{
    const DIRECTION_RIGHT = 'right';
    const DIRECTION_DOWN = 'down';

    public function getName(): string;

    public function getLength(): int;

    public function getX(): int;

    public function getY(): int;

    public function getDirection(): string;

    public function isVertical(): bool;

    public function isHorizontal(): bool;

    public function increaseDamage(): void;

    public function hasSunk(): bool;
}