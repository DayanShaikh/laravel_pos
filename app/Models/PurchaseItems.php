<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseItems extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'item_id',
        'purchase_price',
        'sale_price',
        'quantity',
        'total',
    ];
}
