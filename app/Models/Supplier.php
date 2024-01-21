<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Purchase;
use App\Models\SupplierPayments;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'address',
        'balance',
    ];

    public function GetPurchase(){
        return $this->hasMany(Purchase::class, 'supplier_id');
    }

    public function GetSupplierPayment(){
        return $this->hasMany(SupplierPayments::class, 'supplier_id');
    }
}
