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

    public function employeeSales($startDate = '', $endDate = '')
    {
        //Default to 06/2020
        // if (empty($startDate) || empty($endDate)) {
        //     $date=date_create("2020-06-01");
        //     $startDate = date_format($date,"Y-m-d");
        //     $endDate = date_format($date,"Y-m-t");
        // }

        // $employeeSales = Employee::withCount(['sales' => function($query) use ($startDate, $endDate){
        //     $query->whereBetween('date', [$startDate, $endDate]);
        // }]);

        return  $this->employeeSalesQuery($startDate, $endDate)->get();
    }

    public function employeeSalesQuery($startDate = '', $endDate = '')
    {
        //Default to 06/2020
        if (empty($startDate) || empty($endDate)) {
            $date=date_create("2020-06-01");
            $startDate = date_format($date,"Y-m-d");
            $endDate = date_format($date,"Y-m-t");
        }

        $employeeSales = Employee::withCount(['sales' => function($query) use ($startDate, $endDate){
            $query->whereBetween('date', [$startDate, $endDate]);
        }]);

        return  $employeeSales->orderBy('sales_count', 'DESC');
    }
}