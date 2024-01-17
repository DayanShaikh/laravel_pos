<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierPayments extends Model
{
    use HasFactory;
    protected $fillable=[
        'supplier_id',
        'date',
        'payment',
        'details'
    ];
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
}
