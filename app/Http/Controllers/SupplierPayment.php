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
        $rowsPerPage = $request->input('rowsPerPage', 10);
        $supplier_id = $request->input('supplier_id') ?? null;
        $suppliers = Supplier::get();
        $supplier_payment = SupplierPayments::with('supplier')
        ->when(!empty($supplier_id), function ($query) use ($supplier_id) {
            $query->where('supplier_id', $supplier_id);
        })->paginate($rowsPerPage);
        return view('supplier_payment.list', compact('supplier_payment', 'supplier_id', 'rowsPerPage', 'suppliers'));
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
            'supplier_id' => ['required'],
            'date' => 'required',
            'payment' => 'required',
        ]);
        // $date_format =Carbon::createFromFormat('d/m/Y', $request->date);

        SupplierPayments::create([
            'supplier_id' => $request->supplier_id,
            'date' => $request->date,
            'payment' => $request->payment,
            'details' => $request->detail,
        ]);
        return redirect()->route('supplier_payment.index')->with('sucess', 'Record Successfully Stored');
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
        return view('supplier_payment.edit', compact('detail', 'supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $supplier_payment = SupplierPayments::find($id);
        $supplier_payment->supplier_id = $request->supplier_id;
        $supplier_payment->date =  $request->date;
        $supplier_payment->payment = $request->payment;
        $supplier_payment->details = $request->detail;
        $supplier_payment->update();
        return to_route('supplier_payment.index')->with('success', 'Record Successfully Update');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $supplier_payment = SupplierPayments::find($id);
        $supplier_payment->delete();
    }

    public function status($id, $status)
    {
        $supp_payment = SupplierPayments::find($id);
        $supp_payment->status = $status;
        $supp_payment->save();
        return to_route('supplier_payment.index')->with('success', 'Status Successfully Update');
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
                SupplierPayments::where('id', $multis)->delete();
            }
            return redirect()->back()->with('message', 'Selected Records delete Successfully');
        }
        if ($action == 'status_on') {
            foreach ($multi as $multis) {
                SupplierPayments::where('id', $multis)->update(['status' => 1]);
            }
            return redirect()->back()->with('message', 'Selected Rocords Status ON Successfully');
        }
        if ($action == 'status_off') {
            foreach ($multi as $multis) {
                SupplierPayments::where('id', $multis)->update(['status' => 0]);
            }
            return redirect()->back()->with('message', 'Selected Rocords Status OFF Successfully');
        }
        if ($action == '') {
            return redirect()->back();
        }
    }
}
