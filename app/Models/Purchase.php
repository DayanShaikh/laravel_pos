<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'supplier_id',
        'total_items',
        'total_price',
        'discount',
        'net_price',
        'note',
    ];

    public function items()
    {
        return $this->hasMany(PurchaseItems::class, 'purchase_id');
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
}
