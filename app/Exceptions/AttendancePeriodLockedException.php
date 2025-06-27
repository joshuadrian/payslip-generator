<?php

namespace App\Exceptions;

class AttendancePeriodLockedException extends MyException
{

    public function __construct()
    {
        parent::__construct('Payroll already processed.', 422);
    }
}
