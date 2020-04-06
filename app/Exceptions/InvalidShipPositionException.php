<?php


namespace App\Exceptions;


use Exception;

class InvalidShipPositionException extends Exception
{
    public function __construct()
    {
        parent::__construct('Invalid Ship position');
    }
}