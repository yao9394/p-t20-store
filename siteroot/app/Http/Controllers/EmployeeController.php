<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\EmployeeService;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Jobs\prepareEmployeeCsvExport;

class EmployeeController extends Controller
{
    private $employeeService;

    public function __construct(EmployeeService $employeeService)
    {
        $this->employeeService = $employeeService;
    }

    // Display employee data.
    public function show(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start' => 'date_format:Y-m-d',
            'end' => 'date_format:Y-m-d',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'invalid data']);
        } else {
            $start = $request->input('start');
            $end = $request->input('end');
        }

        return response()->json($this->employeeService->employeeSales($start, $end));
    }

    public function orderCsv(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start' => 'date_format:Y-m-d',
            'end' => 'date_format:Y-m-d',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'invalid data']);
        } else {
            $now = Carbon::now();
            prepareEmployeeCsvExport::dispatch($request->all(), $this->employeeService, $request->user(), $now);
        }

        return response()->json(['success' => 'You csv export has been ordered, csv file can be found in "My Folder" when it is ready.']);
    }
}
