<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $Items1 = Menu::create([
            'title' => 'Items',
            'url' =>'#',
            'parent_id' => null,
            'small_icon' =>'<i class="fa fa-store ms-2"></i>',
            'icon' =>'',
        ]);
        Menu::create([
            'title' => 'Items',
            'url' =>'/item',
            'parent_id' => $Items1->id,
            'small_icon' =>'<i class="fa-solid fa-tag"></i>',
            'icon' =>'',
        ]);
        $invetory = Menu::create([
            'title' => 'Inventory',
            'url' =>'#',
            'parent_id' => null,
            'small_icon' =>'<i class="fa-solid fa-warehouse ms-2"></i>',
            'icon' =>'',
        ]);
        Menu::create([
            'title' => 'Purchase',
            'url' =>'/purchase',
            'parent_id' => $invetory->id,
            'small_icon' =>'<i class="fa-solid fa-cart-shopping"></i>',
            'icon' =>'',
        ]);
        Menu::create([
            'title' => 'Supplier',
            'url' =>'/supplier',
            'parent_id' => $invetory->id,
            'small_icon' =>'<i class="fa-solid fa-cart-flatbed"></i>',
            'icon' =>'',
        ]);
        Menu::create([
            'title' => 'Supplier Payments',
            'url' =>'/supplier_payment',
            'parent_id' => $invetory->id,
            'small_icon' =>'<i class="fa-solid fa-cart-flatbed"></i>',
            'icon' =>'',
        ]);
        $Sales = Menu::create([
            'title' => 'Sales',
            'url' =>'#',
            'parent_id' => null,
            'small_icon' =>'<i class="fa-solid fa-sack-dollar"></i>',
            'icon' =>'',
        ]);
        Menu::create([
            'title' => 'Customer',
            'url' =>'/customer',
            'parent_id' => $Sales->id,
            'small_icon' =>'<i class="fa-solid fa-cart-flatbed"></i>',
            'icon' =>'',
        ]);
    }
}
