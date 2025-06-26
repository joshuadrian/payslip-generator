<?php

namespace App\Exceptions;

use Exception;

class OvertimeSubmittedException extends Exception
{

    public function __construct()
    {
        parent::__construct("You have already submitted an overtime for today", 409);
    }
}
