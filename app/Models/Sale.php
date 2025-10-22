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

    public function  customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
    public function saleItem()
    {
        return $this->hasMany(SaleItem::class, 'sale_id', 'id');
    }
}
