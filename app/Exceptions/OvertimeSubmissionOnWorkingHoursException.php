<?php

namespace App\Exceptions;

use Exception;

class OvertimeSubmissionOnWorkingHoursException extends Exception
{

    public function __construct()
    {
        parent::__construct("You can't submit overtime during work hours", 409);
    }
}
