<?php


namespace App\Exceptions;


class OutOfGridException extends BattleShipException
{
    public function __construct()
    {
        parent::__construct('Ship position is not within the grid!');
    }
}