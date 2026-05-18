<?php

namespace App\Exceptions;

class InvalidOrderTransitionException extends \RuntimeException
{
    public function __construct(string $message = 'This status transition is not permitted from the current order status.')
    {
        parent::__construct($message);
    }
}
