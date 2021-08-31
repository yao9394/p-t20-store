<?php

namespace App\Services;

use App\Models\Employee;

class EmployeeService {
    public function __construct()
    {

    }

    public function getEmployeeIdAndName()
    {
        return Employee::select('id', 'name')->get();
    }
}