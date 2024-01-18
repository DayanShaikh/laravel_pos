<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Customer_Payment;
use Carbon\Carbon;

class Customer_PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sn =1;
        $customer =Customer::all();
        $rowsPerPage = $request->input('rowsPerPage', 10);
        $name = $request->input('name') ?? "";
        if (!empty($name)) {
            $customer_payment = Customer_Payment::where('name','id', 'like', '%' . $name . '%')->paginate($rowsPerPage);
        } else {
            $customer_payment = Customer_Payment::with('customer')->paginate($rowsPerPage);
        }
        
        return view('customer_payment.list', compact('customer','customer_payment','sn','name','rowsPerPage'));
    
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customer = Customer::all();
        return view('customer_payment.create',compact('customer'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate =$request->validate([
            'customer_id' => 'required',
            'date'=>'required',
            'payment'=>'required'
        ]);
        $date_format =Carbon::createFromFormat('d/m/Y', $request->date);
        Customer_Payment::create([
            'customer_id'=>$request->customer_id,
            'date'=>$date_format,
            'payment'=>$request->payment,
            'details'=>$request->detail,
        ]);
        return to_route('customer_payment.index')->with('message','Record Successfully Added');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $detail = Customer_Payment::find($id);
        $customer =Customer::all();
        return view('customer_payment.edit',compact('detail','customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'customer_id'=>'required',
            'date'=>'required',
            'payment'=>'required'
        ]);
        $date_format =Carbon::createFromFormat('d/m/Y', $request->date);

        $detail= Customer_Payment::find($id);
        $detail->customer_id=$request->customer_id;
        $detail->date=$date_format;
        $detail->payment=$request->payment;
        $detail->details=$request->detail;
        $detail->update();
        
        return to_route('customer_payment.index')->with('message','Record Successfully Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $detail =Customer_Payment::find($id);
        $detail->delete();
    }
    public function status($id,$status)
    {
        $supp_payment = Customer_Payment::find($id);
        $supp_payment->status = $status;
        $supp_payment->save();
        return to_route('customer_payment.index')->with('success','Status Successfully Update');
        
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
                Customer_Payment::where('id', $multis)->delete();
            }
            return redirect()->back()->with('message', 'Selected Records delete Successfully');
        }
        if ($action == 'status_on') {
            foreach ($multi as $multis) {
                Customer_Payment::where('id', $multis)->update(['status' => 1]);
            }
            return redirect()->back()->with('message', 'Selected Rocords Status ON Successfully');
        }
        if ($action == 'status_off') {
            foreach ($multi as $multis) {
                Customer_Payment::where('id', $multis)->update(['status' => 0]);
            }
            return redirect()->back()->with('message', 'Selected Rocords Status OFF Successfully');
        }
        if ($action == '') {
            return redirect()->back();
        }
    }
    
}
