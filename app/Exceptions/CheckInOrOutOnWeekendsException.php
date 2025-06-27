<?php

namespace App\Exceptions;

class CheckInOrOutOnWeekendsException extends MyException
{

    public function __construct()
    {
        parent::__construct('Check in or check out cannot be done in weekends.', 422);
    }
}
