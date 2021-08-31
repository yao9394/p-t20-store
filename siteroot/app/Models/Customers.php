<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function sales()
    {
        return $this->hasMany(Sales::class, 'customer_id');
    }
}
