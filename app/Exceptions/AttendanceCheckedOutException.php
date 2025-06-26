<?php

namespace App\Exceptions;

use Exception;

class AttendanceCheckedOutException extends Exception
{

    public function __construct()
    {
        parent::__construct("You have already checked out for today", 409);
    }
}
