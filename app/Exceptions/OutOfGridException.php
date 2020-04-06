<?php


namespace App\Exceptions;


use Exception;

class OutOfGridException extends Exception
{
    public function __construct()
    {
        parent::__construct('Ship position is not within the grid!');
    }
}