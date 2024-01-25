<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable=([
        'account_id',
        'refrence_id',
        'date',
        'amount',
        'details',

    ]);
    public function account_to()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
    public function account_from()
    {
        return $this->belongsTo(Account::class, 'refrence_id');
    }
}
