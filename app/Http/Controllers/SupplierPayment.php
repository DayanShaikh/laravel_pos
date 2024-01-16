<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplierPayments;
use App\Models\Supplier;
use Carbon\Carbon;

class SupplierPayment extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sn =1;
        $supplier =Supplier::all();
        $rowsPerPage = $request->input('rowsPerPage', 10);
        $name = $request->input('name') ?? "";
        if (!empty($name)) {
            $supplier_payment = SupplierPayments::where('name','id', 'like', '%' . $name . '%')->paginate($rowsPerPage);
        } else {
            $supplier_payment = SupplierPayments::paginate($rowsPerPage);
        }
        return view('supplier_payment.list', compact('supplier','supplier_payment','sn','name','rowsPerPage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $supplier = Supplier::all();
        return view('supplier_payment.create', compact('supplier'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validete = $request->validate([
            'supplier_id'=>'required',
            'date'=>'required',
            'payment'=>'required',
        ]);
        $date_format =Carbon::createFromFormat('d/m/Y', $request->date);

        SupplierPayments::create([
            'supplier_id'=>$request->supplier_id,
            'date'=>$date_format,
            'payment'=>$request->payment,
            'details'=>$request->detail,
        ]);
        $supplier = Supplier::find($request->supplier_id);
        $balance =$supplier->balance -$request->payment;
        $supplier->update(['balance'=>$balance]);
        return redirect()->route('supplier_payment.index')->with('sucess','Record Successfully Stored');
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
    public function edit($id)
    {
        $detail = SupplierPayments::find($id);
        $supplier = Supplier::all();
        return view('supplier_payment.edit', compact('detail','supplier'));
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
    public function status(string $id)
    {
        //
    }
    

}
