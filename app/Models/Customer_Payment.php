<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer_Payment extends Model
{
    use HasFactory;
    protected $fillable=([
        'customer_id',
        'date',
        'payment',
        'details'
    ]);
    public function customer(){
        return $this->belongsTo(Customer::class,'customer_id');
    }
}
