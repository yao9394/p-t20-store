<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\SalesService;
use Illuminate\Support\Facades\Validator;

class SalesController extends Controller
{
    public function __construct(SalesService $salesService)
    {
        $this->salesService = $salesService;
    }

    // Output sales data for sales chart in Dashboard.
    public function dateCount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start' => 'date_format:Y-m-d',
            'end' => 'date_format:Y-m-d',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'date format invalid']);
        } else {
            $start = $request->input('start');
            $end = $request->input('end');
        }

        return response()->json($this->salesService->dateCount($start, $end));
    }

     // Return available data date range for date picker.
     public function dateRange()
     {
        return response()->json($this->salesService->salesDataDateRange());
     }
}