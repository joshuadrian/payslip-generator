<?php

namespace App\Exceptions;

class AuthException extends MyException
{

    public function __construct()
    {
        parent::__construct('Invalid credentials.', 401);
    }
}
