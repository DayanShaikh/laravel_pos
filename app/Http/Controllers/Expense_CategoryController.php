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
        //
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
        //
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
}
