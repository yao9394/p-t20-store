<?php

namespace App\Services;

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
}