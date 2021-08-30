<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;

    protected $primaryKey = 'invoiceId';

    public function customer() {
        return $this->belongsTo(Customers::class, 'customer_id');
    }

    public function employee() {
        return $this->belongsTo(Employee::class, 'sales_person');
    }

    public function product() {
        return $this->belongsTo(Products::class, 'product_id');
    }
}
