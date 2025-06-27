<?php

namespace App\Exceptions;

class OvertimeSubmittedException extends MyException
{

    public function __construct()
    {
        parent::__construct("You have already submitted an overtime for today.", 409);
    }
}
