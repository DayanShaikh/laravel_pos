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
        $suppliers = Supplier::all();
        $rowsPerPage = $request->input('rowsPerPage', 10);
        $from_date = $request->input('from_date') ? Carbon::createFromFormat('d/m/Y', $request->input('from_date') ?? Carbon::now())->format('Y-m-d') : Carbon::now();
        $to_date = $request->input('to_date') ? Carbon::createFromFormat('d/m/Y', $request->input('to_date'))->format('Y-m-d') : Carbon::now();
        $supplier_id = $request->input('supplier_id');

        $purchase = Purchase::with('supplier')
            ->when($from_date != Carbon::now() && $to_date != Carbon::now(), function ($query) use ($from_date, $to_date, $supplier_id) {
                $query->whereBetween('date', [$from_date, $to_date]);
            })
            ->where('is_return', false)->paginate($rowsPerPage);
        return view('purchase.list', compact('purchase', 'rowsPerPage', 'from_date', 'to_date', 'suppliers', 'supplier_id',));
    }

    public function return(Request $request)
    {
        $sn = 1;
        $rowsPerPage = $request->input('rowsPerPage', 10);
        $from_date = $request->input('from_date') ?? "";
        $to_date = $request->input('to_date') ?? "";
        $supplier_id = $request->input('supplier_id') ?? "";

        if (!empty($from_date) && !empty($to_date) && !empty($supplier_id)) {
            $format_from_date = Carbon::createFromFormat('d/m/Y', $from_date)->format('Y-m-d');
            $format_to_date = Carbon::createFromFormat('d/m/Y', $to_date)->format('Y-m-d');
            $purchase = Purchase::where('date', '>=', $format_from_date)->where('date', '<=', $format_to_date)->where('supplier_id', $supplier_id)->where('is_return', true)->paginate($rowsPerPage);
        } elseif (empty($from_date) && empty($to_date) && !empty($supplier_id)) {
            $purchase = Purchase::where('supplier_id', $supplier_id)->paginate($rowsPerPage);
        } elseif (!empty($from_date) && !empty($to_date) && empty($supplier_id)) {
            $format_from_date = Carbon::createFromFormat('d/m/Y', $from_date)->format('Y-m-d');
            $format_to_date = Carbon::createFromFormat('d/m/Y', $to_date)->format('Y-m-d');
            $purchase = Purchase::where('date', '>=', $format_from_date)->where('date', '<=', $format_to_date)->where('is_return', true)->paginate($rowsPerPage);
        } else {
            $purchase = Purchase::with('supplier')->where('is_return', true)->paginate($rowsPerPage);
        }
        $suppliers = Supplier::all();
        return view('purchase.return_list', compact('purchase', 'rowsPerPage', 'sn', 'from_date', 'to_date', 'suppliers', 'supplier_id'));
    }

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
        $data = json_decode($request->input('purchase'), true);
        if (empty($data['date'])) {
            return response()->json(['status' => 0, 'error' => '"Fields with * are mandatory"']);
        }
        foreach ($data['items'] as $items) {
            if ($items['purchase_price'] == 0 && $items['sale_price'] == 0 && $items['quantity'] == 0) {
                return response()->json(['status' => 0, 'error' => '"Fields with * are mandatory"']);
            }
        }
        $format_date = Carbon::createFromFormat('d/m/Y', $data['date']);
        $purchase = Purchase::create([
            'date' => $format_date->format('Y-m-d'),
            'supplier_id' => $data['supplier_id'],
            'total_items' => $data['quantity'],
            'total_price' => $data['total'],
            'discount' => $data['discount'],
            'net_price' => $data['net_total'],
            'note' => $data['notes'],
        ]);
        if ($data['is_return'] == 1) {
            $purchase->is_return = true;
            $purchase->save();
        }
        foreach ($data['items'] as $items) {
            $pur_items = PurchaseItems::create([
                'purchase_id' => $purchase->id,
                'item_id' => $items['item_id'],
                'purchase_price' => $items['purchase_price'],
                'sale_price' => $items['sale_price'],
                'quantity' => $items['quantity'],
                'total' => $items['total'],
            ]);
            $pur_items = Item::where('id', $items['item_id'])->first();
            if ($data['is_return'] == 0) {
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

        $data = json_decode($request->input('purchase'), true);
        foreach ($data['items'] as $item) {
            $purchase_item = PurchaseItems::where('purchase_id', $id)->where('item_id', $item['item_id'])->first();
            $pur_items = Item::where('id', $item['item_id'])->first();
            $pur_items->quantity -= $purchase_item['quantity'];
            $pur_items->save();
        }
        $format_date = Carbon::createFromFormat('d/m/Y', $data['date']);
        $purchase = Purchase::find($id);
        $purchase->date = $format_date->format('Y-m-d');
        $purchase->supplier_id = $data['supplier_id'];
        $purchase->total_items = $data['total_items'];
        $purchase->total_price = $data['total_price'];
        $purchase->discount = $data['discount'];
        $purchase->net_price = $data['net_price'];
        $purchase->note = $data['note'];
        if ($data['is_return'] == 1) {
            $purchase->is_return = true;
        } else {
            $purchase->is_return = false;
        }
        $purchase->save();
        foreach ($data['items'] as $items) {
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
            if ($data['is_return'] == 1) {
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
