<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Item;
use Carbon\Carbon;

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

    public function store(Request $request)
    {
        // return json_decode($request->input('purchase'), true);
        $data = json_decode($request->input('purchase'), true);
        $format_date = Carbon::parse($data['datetime_added'])->format('Y-m-d');
        $purchase = Purchase::create([
            'date' => $format_date,
            'supplier_id' => $data['supplier_id'],
            'total_items' => $data['quantity'],
            'total_price' => $data['total'],
            'discount' => $data['discount'],
            'net_price' => $data['net_total'],
            'note' => $data['notes'],
        ]);

        foreach ($data['items'] as $items){
            
        }

    }
}
