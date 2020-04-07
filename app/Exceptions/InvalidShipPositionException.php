<?php


namespace App\Exceptions;


class InvalidShipPositionException extends BattleShipException
{
    public function __construct()
    {
        parent::__construct('Invalid Ship position');
    }
}