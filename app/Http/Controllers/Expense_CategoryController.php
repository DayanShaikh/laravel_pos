<?php

namespace App\Http\Controllers;

use App\Models\Expense_Category;
use Illuminate\Http\Request;

class Expense_CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sn = 1;
        $rowsPerPage = $request->input('rowsPerPage', 10);
        $title = $request->input('title') ?? "";
        if (!empty($title)) {
            $expense_category  = Expense_Category::where('title', 'like', '%' . $title . '%')->paginate($rowsPerPage);
        } else {
            $expense_category = Expense_Category::paginate($rowsPerPage);
        }
        return view('expense_category.list', compact('expense_category', 'rowsPerPage', 'sn', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('expense_category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate =$request->validate([
            'title'=>'required',

        ]);
        Expense_Category::create($validate);
        return to_route('expense_category.index')->with('message','Record Successfully Submited');
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
        $detail = Expense_Category::find($id);
        return view('expense_category.edit',compact('detail'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate(['title'=>'required']);
        $expense_categroy  = Expense_Category::find($id);
        $expense_categroy->title=$request->title;
        $expense_categroy->save();
        return to_route('expense_category.index')->with('message','Record Successfully Updated');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $detail = Expense_Category::find($id);
        $detail->delete();
        return to_route('expense_category.index')->with('message','Record Successfully Deleted ');
    }
    public function status($id, $status)
    {
        $item = Expense_Category::find($id);
        $item->status = $status;
        $item->save();
        return redirect()->back()->with('message', 'Status Update Successfully');
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
                Expense_Category::where('id', $multis)->delete();
            }
            return redirect()->back()->with('message', 'Selected Records delete Successfully');
        }
        if ($action == 'status_on') {
            foreach ($multi as $multis) {
                Expense_Category::where('id', $multis)->update(['status' => 1]);
            }
            return redirect()->back()->with('message', 'Selected Rocords Status ON Successfully');
        }
        if ($action == 'status_off') {
            foreach ($multi as $multis) {
                Expense_Category::where('id', $multis)->update(['status' => 0]);
            }
            return redirect()->back()->with('message', 'Selected Rocords Status OFF Successfully');
        }
        if ($action == '') {
            return redirect()->back();
        }
    }
}
