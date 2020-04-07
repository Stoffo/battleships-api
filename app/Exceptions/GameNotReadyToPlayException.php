<?php


namespace App\Exceptions;


class GameNotReadyToPlayException extends BattleShipException
{
    public function __construct()
    {
        parent::__construct('Game is not ready to play yet! (All ships MUST be placed)');
    }
}