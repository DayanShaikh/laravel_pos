<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Expense_Category;

class Manage_Expense extends Model
{
    use HasFactory;
    protected $fillable=([
        'expense_id',
        'date',
        'payment',
        'details',

    ]);
    public function expense()
    {
        return $this->belongsTo(Expense_Category::class, 'expense_id');
    }
}
