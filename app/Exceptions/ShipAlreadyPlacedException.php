<?php


namespace App\Exceptions;


use App\Contracts\ShipInterface;

class ShipAlreadyPlacedException extends BattleShipException
{
    public function __construct(ShipInterface $ship)
    {
        parent::__construct($ship->getName() . 'is already placed in the grid!');
    }
}