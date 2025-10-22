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

    public function index(Request $request)
    {
        $customers = Customer::get();
        $rowsPerPage = $request->input('rowsPerPage', 10);
        $from_date = $request->input('from_date') ? Carbon::createFromFormat('d/m/Y', $request->input('from_date'))->format('Y-m-d') : Carbon::now()->format('Y-m-d');
        $to_date = $request->input('to_date') ? Carbon::createFromFormat('d/m/Y', $request->input('to_date'))->format('Y-m-d') : Carbon::now()->format('Y-m-d');
        $customer_id = $request->input('customer_id');
        $sales = Sale::whereRaw('DATE(date) BETWEEN ? AND ?', [$from_date, $to_date])->when($customer_id, function ($query) use ($customer_id) {
            $query->where('customer_id', $customer_id);
        })->where('is_return', 0)->paginate($rowsPerPage);
        // })->where('is_return', 0)->toRawSql();
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
        $sale = Sale::create([
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
        $customers = customer::where('status', 1)->get();
        $items = Item::where('status', 1)->get();
        // $accounts = Account::where('status', 1)->get();
        $sale = Sale::with('saleItem')->findOrFail($id);
        // Prepare sale data to be passed to the view
        $saleData = $sale->toArray();
        $saleData['sale_item'] = $sale->saleItem->toArray();
        // $saleData['sale_payment'] = $sales->salePayment->toArray();

        return view('sale.addEdit', compact('sale', 'customers', 'items', 'saleData'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Gate::authorize('update', Sale::class);
        $validatedData = $request->validate([
            'customer' => ['required'],
            'date' => 'required|date',
            'items' => 'required|array',
            'items.*.item_id' => 'required',
            'items.*.price' => 'required|numeric',
            'items.*.quantity' => 'required|integer'
        ]);
        $date = Carbon::parse($request->date)->format('Y-m-d');
        $sale = Sale::where('id', $id)->update([
            'customer_id' => $request->customer,
            'total_quantity' => $request->totalQuantity,
            'total_amount' => $request->totalPrice,
            'discount' => $request->discount,
            'net_amount' => $request->netTotal,
            'date' => $date,
        ]);
        foreach ($request->items as  $item) {
            $sale_item = SaleItem::where('sale_id', $id)->where('item_id', $item['item_id'])->first();
            $items = Item::find($item['item_id']);
            if ($sale_item) {
                $items->quantity = $items->quantity + $sale_item->quantity;
                $sale_item->update([
                    'sale_id' => $id,
                    'item_id' => $item['item_id'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'discount' => $item['discount'],
                ]);
                $items->quantity -= $item['quantity'];
                $items->update();
            } else {
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
        }
        // $sale_payment = SalePayment::where('sale_id', $id)->update([
        //     'sale_id' => $id,
        //     'account_id' => $request->payment_method,
        //     'net_total' => $request->netTotal,
        //     'recieved_payment' => $request->recievedPayment,
        //     'return_payment' => $request->returnPayment,
        // ]);

        return response()->json([
            'status' => true,
            'sale_id' => $id,
            'message' => 'Record Updated Successfully'
        ]);
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
