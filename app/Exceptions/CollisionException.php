<?php


namespace App\Exceptions;


use App\Contracts\ShipInterface;

class CollisionException extends BattleShipException
{
    public function __construct(ShipInterface $ship)
    {
        parent::__construct($ship->getName() . ' is in the way of the desired coordinates!');
    }
}