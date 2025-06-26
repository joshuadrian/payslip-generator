<?php

namespace App\Exceptions;

use Exception;

class UniqueAttendancePeriodException extends MyException
{

    public function __construct()
    {
        parent::__construct("Attendance period already exists.", 409);
    }
}
