<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use Carbon\Carbon;

class AccountController extends Controller
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
            $account = Account::where('name','id', 'like', '%' . $name . '%')->paginate($rowsPerPage);
        } else {
            $account = Account::paginate($rowsPerPage);
        }
        // return $supplier_payment;
        return view('account.list', compact('account','sn','name','rowsPerPage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('account.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate= $request->validate([
            'title'=>'required',
            'payment'=>'required',
            'type'=>'required',
            
        ]);
        
        
       Account::create([
       'title'=>$request->title,
       'type'=>$request->type ,
       'payment'=>$request->payment,
       'description'=>$request->desc,
       ]);
       

        return to_route('account.index')->with('message','Record Successfully Submited');
        
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
        $account =Account::find($id);
        return view('account.edit',compact('account'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $account = Account::find($id);
        $account->title= $request->title;
        $account->type= $request->type;
        $account->payment= $request->payment;
        $account->description= $request->description;
        $account->update();
        return to_route('account.index')->with('message','Record Successfully Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $account = Account::find($id);
        $account->delete();
        return to_route('account.index')->with('message','Record Successfully Deleted');
    }
    public function status($id,$status)
    {
        $supp_payment = Account::find($id);
        $supp_payment->status = $status;
        $supp_payment->save();
        return to_route('account.index')->with('success','Status Successfully Update');
        
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
                Account::where('id', $multis)->delete();
            }
            return redirect()->back()->with('message', 'Selected Records delete Successfully');
        }
        if ($action == 'status_on') {
            foreach ($multi as $multis) {
                Account::where('id', $multis)->update(['status' => 1]);
            }
            return redirect()->back()->with('message', 'Selected Rocords Status ON Successfully');
        }
        if ($action == 'status_off') {
            foreach ($multi as $multis) {
                Account::where('id', $multis)->update(['status' => 0]);
            }
            return redirect()->back()->with('message', 'Selected Rocords Status OFF Successfully');
        }
        if ($action == '') {
            return redirect()->back();
        }
    }
    
}
