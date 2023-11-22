<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ItemCategory;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_category_id',
        'title',
        'unit_price',
        'sale_price',
        'quantity',
    ];

    public function ItemCategory(){
        return $this->belongsTo(ItemCategory::class);
    }
}
