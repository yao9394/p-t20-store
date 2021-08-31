<?php

namespace App\Services;

use App\Models\Customers;

class CustomerService {
    public function __construct()
    {

    }

    public function getCustomerIdAndName()
    {
        return Customers::select('id', 'full_name')->get();
    }
}