<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\Account;
use Carbon\Carbon;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sn =1;
        $rowsPerPage = $request->input('rowsPerPage', 10);
        $name = $request->input('name') ?? "";
        if (!empty($name)) {
            $transaction = Transaction::where('name','id', 'like', '%' . $name . '%')->paginate($rowsPerPage);
        } else {
        }
        $transaction = Transaction::with('account_to','account_from')->paginate($rowsPerPage);
        $account =Account::all();
        return view('transaction.list', compact('transaction','account','sn','name','rowsPerPage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $account = Account::all();
        return view('transaction.create',compact('account'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'account_id'=>'required',
            'refrence_id'=>'required',
            'date'=>'required',
            'amount'=>'required'
        ]);
        $date= Carbon::createFromFormat('d/m/Y',$request->date);
        Transaction::create([
            'account_id'=>$request->account_id,
            'refrence_id'=>$request->refrence_id,
            'date'=>$date,
            'amount'=>$request->amount,
            'details'=>$request->detail,
        ]);
        return to_route('transaction.index')->with('message','Record Successfully Submited');
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
        $transaction = Transaction::find($id);
        $account =Account::all();
        return view('transaction.edit',compact('transaction','account'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id,) 
    {
        $transaction = Transaction::find($id);
        $request->validate([
            'account_id'=>'required',
            'refrence_id'=>'required',
            'date'=>'required',
            'amount'=>'required'
        ]);
        $date= Carbon::createFromFormat('d/m/Y',$request->date);
        $transaction->update([
            'account_id'=>$request->account_id,
            'refrence_id'=>$request->refrence_id,
            'date'=>$date,
            'amount'=>$request->amount,
            'details'=>$request->detail,
        ]);
        return to_route('transaction.index')->with('message','Record Successfully updated');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $transaction = Transaction::find($id);
        $transaction->delete();
        return to_route('transaction.index')->with('message','Record Successfully Deleted');
    }
    public function status($id,$status)
    {
        $supp_payment = Transaction::find($id);
        $supp_payment->status = $status;
        $supp_payment->save();
        return to_route('transaction.index')->with('success','Status Successfully Update');
        
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
                Transaction::where('id', $multis)->delete();
            }
            return redirect()->back()->with('message', 'Selected Records delete Successfully');
        }
        if ($action == 'status_on') {
            foreach ($multi as $multis) {
                Transaction::where('id', $multis)->update(['status' => 1]);
            }
            return redirect()->back()->with('message', 'Selected Records Status ON Successfully');
        }
        if ($action == 'status_off') {
            foreach ($multi as $multis) {
                Transaction::where('id', $multis)->update(['status' => 0]);
            }
            return redirect()->back()->with('message', 'Selected Records Status OFF Successfully');
        }
        if ($action == '') {
            return redirect()->back();
        }
    }
}
