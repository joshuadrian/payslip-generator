<?php

namespace App\Exceptions;

class AttendancePeriodNotFoundException extends MyException
{

    public function __construct()
    {
        parent::__construct("Attendance period doesn't exists for today.", 409);
    }
}
