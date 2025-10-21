<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Item;
use App\Models\SaleItem;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $customers = Customer::get();
        $rowsPerPage = $request->input('rowsPerPage', 10);
        $from_date = $request->input('from_date') ? Carbon::createFromFormat('d/m/Y', $request->input('from_date') ?? Carbon::now())->format('Y-m-d') : Carbon::now();
        $to_date = $request->input('to_date') ? Carbon::createFromFormat('d/m/Y', $request->input('to_date'))->format('Y-m-d') : Carbon::now();
        $customer_id = $request->input('customer_id');
        $sales = Sale::when($from_date != Carbon::now() && $to_date != Carbon::now(), function ($query) use ($from_date, $to_date, $customer_id) {
            $query->whereBetween('date', [$from_date, $to_date]);
        })->where('is_return', false)->paginate($rowsPerPage);
        return view('sale.list', compact('sales', 'customers', 'rowsPerPage', 'from_date', 'to_date', 'customer_id'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::where('status', 1)->get();
        $items = Item::where('status', 1)->get();
        // $accounts = Account::where('status', 1)->get();
        return view('sale.addEdit', compact('customers', 'items'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer' => ['required'],
            'date' => 'required|date',
            'items' => 'required|array',
            'items.*.item_id' => 'required',
            'items.*.price' => 'required',
            'items.*.quantity' => 'required'
        ]);

        foreach ($request->items as $index => $item) {
            $itemRecord = Item::find($item['item_id']);
            if ($item['quantity'] > $itemRecord->quantity) {
                $errors = [];
                $errors[$index] = 'Quantity must be lower than available stock';
                return response()->json([
                    'message' => $errors,
                    'status' => false
                ]);
            }
        }
        // return DB::transiction(function () use ($request) {
        $date = Carbon::parse($request->date)->format('Y-m-d');
        $sale = sale::create([
            'customer_id' => $request->customer,
            'total_quantity' => $request->totalQuantity,
            'total_amount' => $request->totalPrice,
            'discount' => $request->totalDiscount,
            'net_amount' => $request->netTotal,
            'date' => $date,
        ]);
        $sale->created_by = auth()->user()->id;

        foreach ($request->items as $item) {
            SaleItem::create([
                'sale_id' => $sale->id,
                'item_id' => $item['item_id'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'discount' => $item['discount'],
                'total' => $item['amount'],
            ]);
            $items = Item::where('id', $item['item_id'])->first();
            $items->quantity -= $item['quantity'];
            $items->update();
        }
        // $sale_payment = SalePayment::create([
        //     'sale_id' => $sale->id,
        //     'account_id' => $request->payment_method,
        //     'net_total' => $request->netTotal,
        //     'recieved_payment' => $request->recievedPayment,
        //     'return_payment' => $request->returnPayment,
        // ]);
        // $sale_payment->created_by = auth()->user()->id;
        return response()->json([
            'sale_id' => $sale->id,
            'message' => 'Record Created Successfully',
            'status' => true
        ]);
        // });
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function fetch()
    {
        $date = Carbon::now()->format('d/m/Y');
        $customer = Customer::all();
        $items = Item::all();
        return response()->json(['customer' => $customer, 'items' => $items, 'date' => $date]);
    }
}
