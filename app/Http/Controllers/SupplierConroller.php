<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\SupplierPayments;
use DB;

class SupplierConroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sn = 1;
        $rowsPerPage = $request->input('rowsPerPage', 10);
        $name = $request->input('name') ?? "";
        if (!empty($name)) {
            $supplier = Supplier::where('name', 'like', '%' . $name . '%')->paginate($rowsPerPage);
        } else {
            $supplier = Supplier::paginate($rowsPerPage);
        }
        return view('supplier.list', compact('supplier', 'rowsPerPage', 'sn', 'name'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('supplier.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);
        Supplier::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'balance' => $request->balance,
        ]);
        return redirect()->route('supplier.index')->with('message', 'Record Create Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function status($id, $status)
    {
        $item = Supplier::find($id);
        $item->status = $status;
        $item->save();
        return redirect()->back()->with('message', 'Status Update Successfully');
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $supplier = Supplier::find($id);
        return view('supplier.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $supplier = Supplier::find($id);
        $supplier->name = $request->name;
        $supplier->phone = $request->phone;
        $supplier->address = $request->address;
        $supplier->balance = $request->balance;
        $supplier->save();
        return redirect()->route('supplier.index')->with('message', 'Record Update Successfully');
    }

    public function ledger($id){
        $sn = 1;
        $supplier = Supplier::find($id);
        $ledger = Purchase::select('date', DB::raw("0 as debit"), "net_price as credit")->where('supplier_id', $id)->union(SupplierPayments::select('date', "payment as debit", DB::raw("0 as credit"))->where('supplier_id', $id))->get();
        return $ledger;
        return view('supplier.ledger', compact('sn', 'supplier'));
    }
    public function destroy(string $id)
    {
        Supplier::find($id)->delete();
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
                Supplier::where('id', $multis)->delete();
            }
            return redirect()->back()->with('message', 'Selected Records delete Successfully');
        }
        if ($action == 'status_on') {
            foreach ($multi as $multis) {
                Supplier::where('id', $multis)->update(['status' => 1]);
            }
            return redirect()->back()->with('message', 'Selected Rocords Status ON Successfully');
        }
        if ($action == 'status_off') {
            foreach ($multi as $multis) {
                Supplier::where('id', $multis)->update(['status' => 0]);
            }
            return redirect()->back()->with('message', 'Selected Rocords Status OFF Successfully');
        }
        if ($action == '') {
            return redirect()->back();
        }
    }
}
