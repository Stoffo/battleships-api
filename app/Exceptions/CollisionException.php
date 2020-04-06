<?php


namespace App\Exceptions;


use App\Contracts\ShipInterface;
use LogicException;

class CollisionException extends LogicException
{
    public function __construct(ShipInterface $ship)
    {
        parent::__construct($ship->getName() . ' is in the way of the desired coordinates!');
    }
}