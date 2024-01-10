<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Item;
use Carbon\Carbon;
use App\Models\PurchaseItems;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $sn = 1;
        $rowsPerPage = $request->input('rowsPerPage', 10);
        $purchase = Purchase::paginate($rowsPerPage);
        return view('purchase.list', compact('purchase', 'rowsPerPage', 'sn'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return view('purchase.create');
    }
    public function fetch(Request $request)
    {
        $date = Carbon::now()->format('d/m/Y');
        $suppliers = Supplier::all();
        $items = Item::all();
        return response()->json(['suppliers' => $suppliers, 'items' => $items, 'date' => $date]);
    }
    public function edit($id)
    {
        return view('purchase.create');
    }
    public function show($id)
    {
        $purchase = Purchase::with('items')->find($id);
        return response()->json(['purchase' => $purchase]);

    }
    public function store(Request $request)
    {
        $data = json_decode($request->input('purchase'), true);
        $format_date = Carbon::parse($data['date'])->format('Y-m-d');
            $purchase = Purchase::create([
                'date' => $format_date,
                'supplier_id' => $data['supplier_id'],
                'total_items' => $data['quantity'],
                'total_price' => $data['total'],
                'discount' => $data['discount'],
                'net_price' => $data['net_total'],
                'note' => $data['notes'],
            ]);
            foreach ($data['items'] as $items) {
                PurchaseItems::create([
                    'purchase_id' => $purchase->id,
                    'item_id' => $items['item_id'],
                    'purchase_price' => $items['purchase_price'],
                    'sale_price' => $items['sale_price'],
                    'quantity' => $items['quantity'],
                    'total' => $items['total'],
                ]);
            }
        return response()->json(['status' => 1, 'message' => 'Purchase created Successfully']);
    }

    public function update(Request $request, $id){

        $data = json_decode($request->input('purchase'), true);
        $format_date = Carbon::parse($data['date'])->format('Y-m-d');
        $purchase = Purchase::find($id);
            $purchase->date = $format_date;
            $purchase->supplier_id = $data['supplier_id'];
            $purchase->total_items = $data['quantity'];
            $purchase->total_price = $data['total'];
            $purchase->discount = $data['discount'];
            $purchase->net_price = $data['net_total'];
            $purchase->note = $data['note'];
            $purchase->save();

            foreach ($data['items'] as $items) {
                $purchase_item = PurchaseItems::where('purchase_id', $id)->get();
                foreach ($purchase_item as $purchase_items) {
                    $purchase_items->item_id = $items['item_id'];
                    $purchase_items->purchase_price = $items['purchase_price'];
                    $purchase_items->sale_price = $items['sale_price'];
                    $purchase_items->quantity = $items['quantity'];
                    $purchase_items->total = $items['total'];
                    $purchase_items->save();
                    if ($items['item_id'] != $purchase_items->item_id) {
                        $purchase_item = new PurchaseItems();
                        $purchase_item->purchase_id = $purchase->id;
                        $purchase_item->item_id = $items['item_id'];
                        $purchase_item->purchase_price = $items['purchase_price'];
                        $purchase_item->sale_price = $items['sale_price'];
                        $purchase_item->quantity = $items['quantity'];
                        $purchase_item->total = $items['total'];
                        $purchase_item->save();
                    }
                }
            }
    }
}
