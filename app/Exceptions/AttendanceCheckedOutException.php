<?php

namespace App\Exceptions;

class AttendanceCheckedOutException extends MyException
{

    public function __construct()
    {
        parent::__construct("You have already checked out for today.", 409);
    }
}
