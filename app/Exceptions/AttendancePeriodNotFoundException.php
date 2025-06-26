<?php

namespace App\Exceptions;

use Exception;

class AttendancePeriodNotFoundException extends Exception
{

    public function __construct()
    {
        parent::__construct("Attendance period doesn't exists for today", 422);
    }
}
