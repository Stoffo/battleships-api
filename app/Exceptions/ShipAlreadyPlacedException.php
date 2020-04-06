<?php


namespace App\Exceptions;


use App\Contracts\ShipInterface;
use LogicException;

class ShipAlreadyPlacedException extends LogicException
{
    public function __construct(ShipInterface $ship)
    {
        parent::__construct($ship->getName() . 'is already placed in the grid!');
    }
}