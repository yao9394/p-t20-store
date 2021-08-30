<?php

namespace App\Services;

use App\Models\Customers;
use App\Models\Employee;
use App\Models\Sales;
use Illuminate\Support\Facades\DB;

class SalesService {
    public function __construct()
    {

    }

    public function dateCount($startDate = '', $endDate = '') 
    {
        //Default to 06/2020
        if (empty($startDate) || empty($endDate)) {
            $date=date_create("2020-06-01");
            $startDate = date_format($date,"Y-m-d");
            $endDate = date_format($date,"Y-m-t");
        }
        return DB::table('sales')
            ->select(DB::raw('count(*) as count, date'))
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    public function salesDataDateRange()
    {
        return [Sales::min('date'), Sales::max('date')];
    }

    public function salesData($startDate = '', $endDate = '', $customer = [], $employee = [])
    {
        //Default to 06/2020
        if (empty($startDate) || empty($endDate)) {
            $date=date_create("2020-06-01");
            $startDate = date_format($date,"Y-m-d");
            $endDate = date_format($date,"Y-m-t");
        }

        $salesData = Sales::with('Customer', 'Employee', 'Product')
                    ->whereBetween('date', [$startDate, $endDate]);
        if (count($customer) > 0) {
            $salesData->whereIn('customer_id', $customer);
        }

        if (count($employee) > 0) {
            $salesData->whereIn('sales_person', $employee);
        }

        return   $salesData->orderBy('date')->get();
    }

    //Customers and employee options for sales data filters.
    public function filters()
    {
        return [
            'clientOptions' => Customers::select('id','full_name')->has('sales')->orderBy('full_name')->get(),
            'employeeOptions' => Employee::has('sales')->orderBy('name')->get()];
    }
}