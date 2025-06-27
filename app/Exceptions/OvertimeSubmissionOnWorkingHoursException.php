<?php

namespace App\Exceptions;

class OvertimeSubmissionOnWorkingHoursException extends MyException
{

    public function __construct()
    {
        parent::__construct("You can't submit overtime during work hours.", 409);
    }
}
