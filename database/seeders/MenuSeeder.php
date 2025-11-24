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
        Menu::truncate();
        $Items1 = Menu::create([
            'title' => 'Items',
            'url' =>'#',
            'parent_id' => null,
            'small_icon' =>'<i class="fa fa-store"></i>',
            'icon' =>'',
        ]);
        Menu::create([
            'title' => 'Items',
            'url' =>'laravel_pos/public/item',
            'parent_id' => $Items1->id,
            'small_icon' =>'<i class="fa fa-tag"></i>',
            'icon' =>'',
        ]);
        $invetory = Menu::create([
            'title' => 'Inventory',
            'url' =>'#',
            'parent_id' => null,
            'small_icon' =>'<i class="fa fa-warehouse"></i>',
            'icon' =>'',
        ]);
        Menu::create([
            'title' => 'Purchase',
            'url' =>'laravel_pos/public/purchase?is_return=0',
            'parent_id' => $invetory->id,
            'small_icon' =>'<i class="fa fa-cart-shopping"></i>',
            'icon' =>'',
        ]);
        Menu::create([
            'title' => 'Supplier',
            'url' =>'laravel_pos/public/supplier',
            'parent_id' => $invetory->id,
            'small_icon' =>'<i class="fa fa-cart-flatbed"></i>',
            'icon' =>'',
        ]);
        Menu::create([
            'title' => 'Supplier Payments',
            'url' =>'laravel_pos/public/supplier_payment',
            'parent_id' => $invetory->id,
            'small_icon' =>'<i class="fa fa-cart-flatbed"></i>',
            'icon' =>'',
        ]);
        $Sales = Menu::create([
            'title' => 'Sales',
            'url' =>'#',
            'parent_id' => null,
            'small_icon' =>'<i class="fa fa-sack-dollar"></i>',
            'icon' =>'',
        ]);
        Menu::create([
            'title' => 'Sales',
            'url' =>'laravel_pos/public/sales',
            'parent_id' => $Sales->id,
            'small_icon' =>'<i class="fa fa-sack-dollar"></i>',
            'icon' =>'',
        ]);
        Menu::create([
            'title' => 'Customer',
            'url' =>'laravel_pos/public/customer',
            'parent_id' => $Sales->id,
            'small_icon' =>'<i class="fa fa-cart-flatbed"></i>',
            'icon' =>'',
        ]);
        Menu::create([
            'title' => 'Customer Payment',
            'url' =>'laravel_pos/public/customer_payment',
            'parent_id' => $Sales->id,
            'small_icon' =>'<i class="fa fa-cart-flatbed"></i>',
            'icon' =>'',
        ]);
        $Accounts = Menu::create([
            'title' => 'Accounts',
            'url' =>'#',
            'parent_id' => null,
            'small_icon' =>'<i class="fa fa-chart-line"></i>',
            'icon' =>'',
        ]);
        Menu::create([
            'title' => 'Expense',
            'url' =>'laravel_pos/public/expense_category',
            'parent_id' => $Accounts->id,
            'small_icon' =>'<i class="fa fa-hand-holding-dollar"></i>',
            'icon' =>'',
        ]);
        Menu::create([
            'title' => 'Manage Expense',
            'url' =>'laravel_pos/public/manage_expense',
            'parent_id' => $Accounts->id,
            'small_icon' =>'<i class="fa-solid fa-hand-holding-dollar"></i>',
            'icon' =>'',
        ]);
        Menu::create([
            'title' => 'Manage Account',
            'url' =>'laravel_pos/public/account',
            'parent_id' => $Accounts->id,
            'small_icon' =>'<i class="fa-solid fa-hand-holding-dollar"></i>',
            'icon' =>'',
        ]);
    }
}
