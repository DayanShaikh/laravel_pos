<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Item;
use Carbon\Carbon;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $sn = 1;
        $rowsPerPage = $request->input('rowsPerPage', 10);
        $purchase = Purchase::paginate($rowsPerPage);
        return view('purchase.list', compact('purchase', 'rowsPerPage', 'sn'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function CreateUpdate(Request $request)
    {
        $supplier = Supplier::all();
        switch ($request->input('action')) {
            case 'get_items':
                $items = Item::all();
                return response()->json($items);
        }
        return view('purchase.create', compact('supplier'));
    }
}
