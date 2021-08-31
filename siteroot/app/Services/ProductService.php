<?php

namespace App\Services;

use App\Models\Products;

class ProductService {
    public function __construct()
    {

    }

    public function getProductIdAndName()
    {
        return Products::select('productId','name')->get();
    }
}