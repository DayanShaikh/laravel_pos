<?php

use App\Http\Controllers\AccountController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\ConfigTypeController;
use App\Http\Controllers\ConfigVariableController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\Customer;
use App\Http\Controllers\ItemCategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\SupplierConroller;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SupplierPayment;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Customer_PaymentController;
use App\Http\Controllers\Expense_CategoryController;
use App\Http\Controllers\Manage_ExpenseController;
use App\Http\Controllers\TransactionController;


Route::get('/', function () {
	return redirect('sign-in');
})->middleware('guest');
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');
Route::get('sign-up', [RegisterController::class, 'create'])->middleware('guest')->name('register');
Route::post('sign-up', [RegisterController::class, 'store'])->middleware('guest');
Route::get('sign-in', [SessionsController::class, 'create'])->middleware('guest');
Route::post('sign-in', [SessionsController::class, 'store'])->middleware('guest')->name('login');
Route::post('verify', [SessionsController::class, 'show'])->middleware('guest');
Route::post('reset-password', [SessionsController::class, 'update'])->middleware('guest')->name('password.update');
Route::get('verify', function () {
	return view('sessions.password.verify');
})->middleware('guest')->name('verify');
Route::get('/reset-password/{token}', function ($token) {
	return view('sessions.password.reset', ['token' => $token]);
})->middleware('guest')->name('password.reset');

Route::post('sign-out', [SessionsController::class, 'destroy'])->middleware('auth')->name('logout');
Route::get('profile', [ProfileController::class, 'create'])->middleware('auth')->name('profile');
Route::post('user-profile', [ProfileController::class, 'update'])->middleware('auth');
Route::group(['middleware' => 'auth'], function () {

	// Permission
	Route::resource('permission', PermissionsController::class);
	Route::post('Permission-bulkaction', [PermissionsController::class, 'bulkAction'])->name('permission.bulkAction');

	// Roles
	Route::resource('role', RolesController::class);
	Route::post('role-bulkaction', [RolesController::class, 'bulkAction'])->name('role.bulkAction');

	//User
	Route::resource('user', UserController::class);
	Route::get('users-update-active-status/{user}/{status}', [UserController::class, 'status'])->name('users.status');
	Route::post('user-bulkaction', [UserController::class, 'bulkAction'])->name('user.bulkAction');

	//config_type
	Route::resource('config_type', ConfigTypeController::class);
	Route::post('config_type_bulkaction', [ConfigTypeController::class, 'bulkAction'])->name('config_type.bulkAction');

	//config_variable
	Route::resource('config_variable', ConfigVariableController::class);
	Route::post('config_type-bulkaction', [ConfigVariableController::class, 'bulkAction'])->name('config_variable.bulkAction');

	//config
	Route::get('config/{id}', [ConfigController::class, 'index'])->name('config.index');
	Route::post('config/store/{id}', [ConfigController::class, 'store'])->name('config.store');

	//expense category
	Route::resource('/expense_category', Expense_CategoryController::class);
	Route::get('/expense_category_status/{id}/{status}', [Expense_CategoryController::class, 'status'])->name('expense_category_status');
	Route::post('/expense_category_bulkAction', [Expense_CategoryController::class, 'bulkAction'])->name('expense_category.bulkAction');

	//Menu
	Route::resource('menu', MenuController::class);
	Route::post('menu-bulkaction', [MenuController::class, 'bulkAction'])->name('menu.bulkAction');

	//item_Category
	Route::resource('item_category', ItemCategoryController::class);
	Route::get('item_category-update-active-status/{item}/{status}', [ItemCategoryController::class, 'status'])->name('item_category.status');
	Route::post('item_category-bulkaction', [ItemCategoryController::class, 'bulkAction'])->name('item_category.bulkAction');

	//item
	Route::resource('item', ItemController::class);
	Route::get('item-update-active-status/{item}/{status}', [ItemController::class, 'status'])->name('item.status');
	Route::post('item-bulkaction', [ItemController::class, 'bulkAction'])->name('item.bulkAction');

	//Supplier
	Route::resource('supplier', SupplierConroller::class);
	Route::get('supplier/ledger/{id}', [SupplierConroller::class, 'ledger'])->name('supplier.ledger');
	Route::get('supplier/print/{id}', [SupplierConroller::class, 'ledger'])->name('supplier.print');
	Route::get('supplier-update-active-status/{item}/{status}', [SupplierConroller::class, 'status'])->name('supplier.status');
	Route::post('supplier-bulkaction', [SupplierConroller::class, 'bulkAction'])->name('supplier.bulkAction');

	//purchase
	Route::get('purchase/', [PurchaseController::class, 'index'])->name('purchase.index');
	Route::get('purchase/create', [PurchaseController::class, 'create'])->name('purchase.create');
	Route::get('purchase/edit/{id}/{message?}', [PurchaseController::class, 'edit'])->name('purchase.edit');
	Route::get('purchase-update-active-status/{item}/{status}', [PurchaseController::class, 'status'])->name('purchase.status');
	Route::delete('purchase/delete/{id}', [PurchaseController::class, 'delete'])->name('purchase.delete');
	Route::post('purchase-bulkaction', [PurchaseController::class, 'bulkAction'])->name('purchase.bulkAction');
	Route::get('purchase_return', [PurchaseController::class, 'return'])->name('purchase.return');

	//supplier payment
	Route::resource('supplier_payment', SupplierPayment::class);
	Route::get('/status/{id}/{status}', [SupplierPayment::class, 'status'])->name('supplier_payment_status');
	Route::get('/supplier_payment_bulkaction', [SupplierPayment::class, 'bulkAction'])->name('supplier_payment.bulkAction');

	//manage Expense
	Route::resource('/manage_expense', Manage_ExpenseController::class);
	Route::get('/manage_expense/{id}/{status}', [Manage_ExpenseController::class, 'status'])->name('manage_expense.status');
	Route::get('/manage_expense_bulkaction', [Manage_ExpenseController::class, 'bulkAction'])->name('manage_expense.bulkAction');

	//account
	Route::resource('/account', AccountController::class);
	Route::get('/account/{id}/{status}', [AccountController::class, 'status'])->name('account_status');
	Route::get('/account_bulkaction', [AccountController::class, 'bulkAction'])->name('account_bulkAction');

	//transaction
	Route::resource('/transaction', TransactionController::class);
	Route::get('/transaction/{id}/{status}', [TransactionController::class, 'status'])->name('transaction_status');
	Route::get('/transaction_bulkaction', [TransactionController::class, 'bulkAction'])->name('transaction_bulkAction');

	//cusotmer
	Route::resource('/customer', CustomerController::class);
	Route::get('/customer_status/{id}/{status}', [CustomerController::class, 'status'])->name('customer.status');
	Route::post('/customer_bulkAction', [CustomerController::class, 'bulkAction'])->name('customer_bulkAction');

	//customer_payment
	Route::resource('/customer_payment', Customer_PaymentController::class);
	Route::get('/customer_payment_status/{id}/{status}', [Customer_PaymentController::class, 'status'])->name('customer_payment_status');
	Route::post('/customer_payment_bulkAction', [Customer_PaymentController::class, 'bulkAction'])->name('customer_payment.bulkAction');

	// sales
	Route::get('purchase/', [PurchaseController::class, 'index'])->name('purchase.index');
	Route::get('purchase/create', [PurchaseController::class, 'create'])->name('purchase.create');
	Route::get('purchase/edit/{id}/{message?}', [PurchaseController::class, 'edit'])->name('purchase.edit');
	Route::get('purchase-update-active-status/{item}/{status}', [PurchaseController::class, 'status'])->name('purchase.status');
	Route::delete('purchase/delete/{id}', [PurchaseController::class, 'delete'])->name('purchase.delete');
	Route::post('purchase-bulkaction', [PurchaseController::class, 'bulkAction'])->name('purchase.bulkAction');
	Route::get('purchase_return', [PurchaseController::class, 'return'])->name('purchase.return');

});
