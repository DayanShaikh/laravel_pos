<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Carbon\Carbon;
use App\Models\Sale;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $customers = Customer::get();
        $rowsPerPage = $request->input('rowsPerPage', 10);
        $from_date = $request->input('from_date') ? Carbon::createFromFormat('d/m/Y', $request->input('from_date') ?? Carbon::now())->format('Y-m-d') : Carbon::now();
        $to_date = $request->input('to_date') ? Carbon::createFromFormat('d/m/Y', $request->input('to_date'))->format('Y-m-d') : Carbon::now();
        $customer_id = $request->input('customer_id');
        $sales = Sale::when($from_date != Carbon::now() && $to_date != Carbon::now(), function ($query) use ($from_date, $to_date, $customer_id) {
            $query->whereBetween('date', [$from_date, $to_date]);
        })->where('is_return', false)->paginate($rowsPerPage);
        return view('sale.list', compact('sales', 'customers', 'rowsPerPage', 'from_date', 'to_date', 'customer_id'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('sale.create');
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
