<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'email' => 'required:email|unique:App\Models\Customers,email',
            'gender' => 'in:Male,Female',
            'street' => 'required|string',
            'city' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'data format invalid, email address could be taken.']);
        } else {
            $customer = new Customers();
            $customer->first_name = $request->input('firstName');
            $customer->last_name = $request->input('lastName');
            $customer->full_name = $customer->first_name . ' ' . $customer->last_name;
            $customer->email = $request->input('email');
            $customer->gender = $request->input('gender');
            $customer->street = $request->input('street');
            $customer->city = $request->input('city');
            $customer->save();
        }

        return response()->json(['success' => 'Customer has been added.']);
    }
}
