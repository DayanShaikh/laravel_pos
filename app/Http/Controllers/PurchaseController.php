<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Item;
use Carbon\Carbon;
use App\Models\PurchaseItems;
use Illuminate\Support\Facades\Cookie;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        if ((int)$request->is_return == Cookie::get('is_return', '')) {
            $is_return = Cookie::get('is_return', '');
        } else {
            Cookie::queue('is_return', (int)$request->is_return, 300);
            $is_return = Cookie::get('is_return', '');
        }
        $dates = array_map('trim', explode(',', $request->input('dates'), 2));
        $from_date =  !empty($dates[0]) ? Carbon::parse($dates[0])->format('Y-m-d') : Carbon::now()->format('Y-m-d');
        $to_date = !empty($dates[1]) ? Carbon::parse($dates[1])->format('Y-m-d') : Carbon::now()->format('Y-m-d');
        $suppliers = Supplier::get();
        $rowsPerPage = $request->input('rowsPerPage', 10);
        $supplier_id = $request->input('supplier_id');
        $purchase = Purchase::with('supplier')
            ->when($from_date != Carbon::now() && $to_date != Carbon::now(), function ($query) use ($from_date, $to_date, $supplier_id) {
                $query->whereBetween('date', [$from_date, $to_date]);
            })
            ->when($supplier_id, function ($query) use ($supplier_id) {
                $query->where('supplier_id', $supplier_id);
            })
            ->where('is_return', $is_return)->paginate($rowsPerPage);
        return view('purchase.list', compact('purchase', 'rowsPerPage', 'from_date', 'to_date', 'suppliers', 'supplier_id', 'request'));
    }

    public function create(Request $request)
    {
        return view('purchase.create');
    }

    public function fetch(Request $request)
    {
        $suppliers = Supplier::all();
        $items = Item::all();
        return response()->json(['suppliers' => $suppliers, 'items' => $items]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required',
            'date' => 'required',
        ]);

        foreach ($request->items as $items) {
            if ($items['purchase_price'] == 0 && $items['sale_price'] == 0 && $items['quantity'] == 0) {
                return response()->json(['status' => 0, 'error' => '"Fields with * are mandatory"']);
            }
        }

        $format_date = Carbon::parse($request->date)->format('Y-m-d');
        $purchase = Purchase::create([
            'date' => $format_date,
            'supplier_id' => $request->supplier_id,
            'total_items' => $request->quantity,
            'total_price' => $request->total,
            'discount' => $request->discount,
            'net_price' => $request->net_total,
            'note' => $request->notes ?? null,
        ]);
        if ($request->is_return == 1) {
            $purchase->is_return = true;
            $purchase->save();
        }
        foreach ($request->items as $items) {
            $pur_items = PurchaseItems::create([
                'purchase_id' => $purchase->id,
                'item_id' => $items['item_id'],
                'purchase_price' => $items['purchase_price'],
                'sale_price' => $items['sale_price'],
                'quantity' => $items['quantity'],
                'total' => $items['total'],
            ]);
            $pur_items = Item::where('id', $items['item_id'])->first();
            if ($request->is_return == 0) {
                $pur_items->quantity += $items['quantity'];
            } else {
                $pur_items->quantity -= $items['quantity'];
            }
            $pur_items->unit_price = $items['purchase_price'];
            $pur_items->sale_price = $items['sale_price'];
            $pur_items->save();
        }
        return response()->json(['status' => 1, 'id' => $purchase->id, 'message' => 'Purchase save Successfully']);
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

    public function update(Request $request, $id)
    {
        foreach ($request->items as $item) {
            $purchase_item = PurchaseItems::where('purchase_id', $id)->where('item_id', $item['item_id'])->first();
            $pur_items = Item::where('id', $item['item_id'])->first();
            $pur_items->quantity -= $purchase_item['quantity'];
            $pur_items->save();
        }
        $format_date = Carbon::parse($request->date)->format('Y-m-d');
        $purchase = Purchase::find($id);
        $purchase->date = $format_date;
        $purchase->supplier_id = $request->supplier_id;
        $purchase->total_items = $request->total_items;
        $purchase->total_price = $request->total_price;
        $purchase->discount = $request->discount;
        $purchase->net_price = $request->net_price;
        $purchase->note = $request->note;
        if ($request->is_return == 1) {
            $purchase->is_return = true;
        } else {
            $purchase->is_return = false;
        }
        $purchase->save();
        foreach ($request->items as $items) {
            $purchase_item = PurchaseItems::where('purchase_id', $id)->where('item_id', $items['item_id'])->first();
            $pur_items = Item::where('id', $items['item_id'])->first();
            $pur_items->quantity - $purchase_item['quantity'];
            $pur_items->save();
            if ($purchase_item) {
                $purchase_item->update([
                    'purchase_price' => $items['purchase_price'],
                    'sale_price' => $items['sale_price'],
                    'quantity' => $items['quantity'],
                    'total' => $items['total'],
                ]);
            } else {
                PurchaseItems::create([
                    'purchase_id' => $purchase->id,
                    'item_id' => $items['item_id'],
                    'purchase_price' => $items['purchase_price'],
                    'sale_price' => $items['sale_price'],
                    'quantity' => $items['quantity'],
                    'total' => $items['total'],
                ]);
            }
            $pur_items2 = Item::where('id', $items['item_id'])->first();
            if ($request->is_return == 1) {
                $pur_items2->quantity -= $items['quantity'];
            } else {
                $pur_items2->quantity += $items['quantity'];
            }
            $pur_items2->save();
        }
        return response()->json(['status' => 1, 'message' => 'Purchase save Successfully']);
    }

    public function status($id, $status)
    {
        $purchase = Purchase::find($id);
        $purchase->status = $status;
        $purchase->save();
        return redirect()->back()->with('message', 'Status Update Successfully');
    }

    public function delete($id)
    {
        Purchase::find($id)->delete();
        PurchaseItems::where('purchase_id', $id)->delete();
        return redirect()->back()->with('message', 'Record delete Successfully');
    }

    public function bulkAction(Request $request)
    {
        $action = $request->action;
        $multi = $request->input('multidelete', []);
        // dd($multi);
        if (empty($multi)) {
            return redirect()->back()->with('error', 'No Records Selected');
        }
        if ($action == 'delete') {
            foreach ($multi as $multis) {
                Purchase::where('id', $multis)->delete();
                PurchaseItems::where('purchase_id', $multis)->delete();
            }
            return redirect()->back()->with('message', 'Selected Records delete Successfully');
        }
        if ($action == 'status_on') {
            foreach ($multi as $multis) {
                Purchase::where('id', $multis)->update(['status' => 1]);
            }
            return redirect()->back()->with('message', 'Selected Rocords Status ON Successfully');
        }
        if ($action == 'status_off') {
            foreach ($multi as $multis) {
                Purchase::where('id', $multis)->update(['status' => 0]);
            }
            return redirect()->back()->with('message', 'Selected Rocords Status OFF Successfully');
        }
        if ($action == '') {
            return redirect()->back();
        }
    }
}
