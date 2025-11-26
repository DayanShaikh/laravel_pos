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
            'small_icon' =>'<i class="material-icons"> list </i>',
            'icon' =>'',
        ]);
        Menu::create([
            'title' => 'Items',
            'url' =>'item',
            'parent_id' => $Items1->id,
            'small_icon' =>'<i class="material-icons"> format_list_bulleted </i>',
            'icon' =>'',
        ]);
        $invetory = Menu::create([
            'title' => 'Inventory',
            'url' =>'#',
            'parent_id' => null,
            'small_icon' =>'<i class="material-icons"> warehouse </i>',
            'icon' =>'',
        ]);
        Menu::create([
            'title' => 'Purchase',
            'url' =>'purchase?is_return=0',
            'parent_id' => $invetory->id,
            'small_icon' =>'<i class="material-icons"> shopping_cart </i>',
            'icon' =>'',
        ]);
        Menu::create([
            'title' => 'Supplier',
            'url' =>'supplier',
            'parent_id' => $invetory->id,
            'small_icon' =>'<i class="material-icons"> assignment_ind </i>',
            'icon' =>'',
        ]);
        Menu::create([
            'title' => 'Supplier Payments',
            'url' =>'supplier_payment',
            'parent_id' => $invetory->id,
            'small_icon' =>'<i class="material-icons"> payments </i>',
            'icon' =>'',
        ]);
        $Sales = Menu::create([
            'title' => 'Sales',
            'url' =>'#',
            'parent_id' => null,
            'small_icon' =>'<i class="material-icons"> point_of_sale </i>',
            'icon' =>'',
        ]);

        Menu::create([
            'title' => 'Sales',
            'url' =>'sales',
            'parent_id' => $Sales->id,
            'small_icon' =>'<i class="material-icons"> local_atm </i>',
            'icon' =>'',
        ]);

        Menu::create([
            'title' => 'Customer',
            'url' =>'customer',
            'parent_id' => $Sales->id,
            'small_icon' =>'<i class="material-icons"> assignment_ind </i>',
            'icon' =>'',
        ]);
        Menu::create([
            'title' => 'Customer Payment',
            'url' =>'customer_payment',
            'parent_id' => $Sales->id,
            'small_icon' =>'<i class="material-icons"> payments </i>',
            'icon' =>'',
        ]);
        $Accounts = Menu::create([
            'title' => 'Accounts',
            'url' =>'#',
            'parent_id' => null,
            'small_icon' =>'<i class="material-icons"> account_balance </i>',
            'icon' =>'',
        ]);
        Menu::create([
            'title' => 'Expense',
            'url' =>'expense_category',
            'parent_id' => $Accounts->id,
            'small_icon' =>'<i class="material-icons"> account_balance_wallet </i>',
            'icon' =>'',
        ]);
        Menu::create([
            'title' => 'Manage Expense',
            'url' =>'manage_expense',
            'parent_id' => $Accounts->id,
            'small_icon' =>'<i class="material-icons"> wallet </i>',
            'icon' =>'',
        ]);
        Menu::create([
            'title' => 'Manage Account',
            'url' =>'account',
            'parent_id' => $Accounts->id,
            'small_icon' =>'<i class="material-icons"> assured_workload </i>',
            'icon' =>'',
        ]);
    }
}
