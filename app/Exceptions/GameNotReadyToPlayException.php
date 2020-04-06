<?php


namespace App\Exceptions;


use Exception;

class GameNotReadyToPlayException extends Exception
{
    public function __construct()
    {
        parent::__construct('Game is not ready to play yet! (All ships MUST be placed)');
    }
}