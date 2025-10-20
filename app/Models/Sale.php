<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'customer_id',
        'total_quantity',
        'total_amount',
        'discount',
        'net_amount',
    ];
}
