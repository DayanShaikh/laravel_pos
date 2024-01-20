<?php

namespace App\Http\Controllers;

use App\Models\Expense_Category;
use Illuminate\Http\Request;
use App\Models\Manage_Expense;
use Carbon\Carbon;

class Manage_ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sn =1;
        $expense =Expense_Category::all();
        $rowsPerPage = $request->input('rowsPerPage', 10);
        $name = $request->input('name') ?? "";
        if (!empty($name)) {
            $manage_expense = Manage_Expense::where('name','id', 'like', '%' . $name . '%')->paginate($rowsPerPage);
        } else {
            $manage_expense = Manage_Expense::with('expense')->paginate($rowsPerPage);
        }
        // return $supplier_payment;
        return view('manage_expense.list', compact('expense','manage_expense','sn','name','rowsPerPage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $expense = Expense_Category::all();
        return view('manage_expense.create',compact('expense'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'expense_id'=>'required',
            'date'=>'required',
            'payment'=>'required'
        ]);
    $date = Carbon::createFromFormat('d/m/Y', $request->date);
        Manage_Expense::create([
            'expense_id'=>$request->expense_id,
            'date'=>$date,
            'payment'=>$request->payment,
            'details'=>$request->detail            
        
        ]);
        return to_route('manage_expense.index')->with('message','Record Successfully Submited');
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
        $expense = Expense_Category::all();
        $detail = Manage_Expense::find($id);
        return view('manage_expense.edit',compact('detail','expense'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'expense_id'=>'required',
            'date'=>'required',
            'payment'=>'required'
        ]);
        $date =Carbon::createFromFormat('d/m/Y', $request->date);
        $expense= Manage_Expense::find($id);
        $expense->expense_id=$request->expense_id;
        $expense->date=$date;
        $expense->payment=$request->payment;
        $expense->details=$request->detail;
        $expense->update();
        return to_route('manage_expense.index')->with('message','Record Successfully Updated');
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $expense = Manage_Expense::find($id);
        $expense->delete();
        return to_route('manage_expense.index')->with('message','Record Successfully Deleted');
    }
    public function status($id,$status)
    {
        $supp_payment = Manage_Expense::find($id);
        $supp_payment->status = $status;
        $supp_payment->save();
        return to_route('manage_expense.index')->with('message','Record Successfully Updated');

        
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
                Manage_Expense::where('id', $multis)->delete();
            }
            return redirect()->back()->with('message', 'Selected Records delete Successfully');
        }
        if ($action == 'status_on') {
            foreach ($multi as $multis) {
                Manage_Expense::where('id', $multis)->update(['status' => 1]);
            }
            return redirect()->back()->with('message', 'Selected Rocords Status ON Successfully');
        }
        if ($action == 'status_off') {
            foreach ($multi as $multis) {
                Manage_Expense::where('id', $multis)->update(['status' => 0]);
            }
            return redirect()->back()->with('message', 'Selected Rocords Status OFF Successfully');
        }
        if ($action == '') {
            return redirect()->back();
        }
    }
    

}
