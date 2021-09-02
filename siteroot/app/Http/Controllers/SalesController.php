<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use Illuminate\Http\Request;
use App\Services\SalesService;
use App\Services\CustomerService;
use App\Services\EmployeeService;
use App\Services\ProductService;
use Illuminate\Support\Facades\Validator;
use App\Jobs\prepareCsvExport;
use Carbon\Carbon;

class SalesController extends Controller
{
    private $salesService;
    private $customerService;
    private $employeeService;
    private $productService;

    public function __construct(SalesService $salesService, CustomerService $customerService, EmployeeService $employeeService, ProductService $productService)
    {
        $this->salesService = $salesService;
        $this->customerService = $customerService;
        $this->employeeService = $employeeService;
        $this->productService = $productService;
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

     // sales data for data grid.
     public function salesData(Request $request)
     {
        $validator = Validator::make($request->all(), [
            'start' => 'date_format:Y-m-d',
            'end' => 'date_format:Y-m-d',
            'customer' => 'array',
            'employee' => 'array',
            'customer.*' => 'exists:sales,customer_id',
            'employee.*' => 'exists:sales,sales_person',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'invalid data']);
        } else {
            $start = $request->input('start');
            $end = $request->input('end');
            $customer = $request->input('customer');
            $employee = $request->input('employee');
        }

        return response()->json($this->salesService->salesData($start, $end, $customer, $employee));
     }

     // Filters options for sales data filters.
     public function filters()
     {
         return response()->json($this->salesService->filters());
     }

     // Filters options for sales creation form.
     public function saleFormFilters()
     {
        return response()->json([
            'products' => $this->productService->getProductIdAndName(),
            'customers' => $this->customerService->getCustomerIdAndName(),
            'employees' => $this->employeeService->getEmployeeIdAndName()
        ]);
     }

     public function addSale(Request $request)
     {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date_format:Y-m-d',
            'product' => 'required|exists:products,productId',
            'customer' => 'required|exists:customers,id',
            'employee' => 'required|exists:employee,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'invalid data']);
        } else {
            $sale = new Sales();
            $sale->date = $request->input('date');
            $sale->sales_person = $request->input('employee');
            $sale->customer_id = $request->input('customer');
            $sale->product_id = $request->input('product');
            $sale->save();
        }

        return response()->json(['success' => 'Sales record has been created.']);
     }

     // Handle requrest to order csv export.
     public function orderCsv(Request $request)
     {
        $validator = Validator::make($request->all(), [
            'start' => 'date_format:Y-m-d',
            'end' => 'date_format:Y-m-d',
            'customer' => 'array',
            'employee' => 'array',
            'customer.*' => 'exists:sales,customer_id',
            'employee.*' => 'exists:sales,sales_person',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'invalid data']);
        } else {
            $now = Carbon::now();
            prepareCsvExport::dispatch($request->all(), $this->salesService, $request->user(), $now);
        }

        return response()->json(['success' => 'You csv export has been ordered, csv file can be found in "My Folder" when it is ready.']);
     }
}