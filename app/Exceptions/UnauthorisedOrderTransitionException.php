<?php

namespace App\Exceptions;

class UnauthorisedOrderTransitionException extends \RuntimeException
{
    public function __construct(string $message = 'You are not authorised to set this order status.')
    {
        parent::__construct($message);
    }
}
