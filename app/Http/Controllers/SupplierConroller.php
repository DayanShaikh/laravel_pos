<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\SupplierPayments;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Session;
use PDF;
use Illuminate\Support\Facades\Cache;

class SupplierConroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $rowsPerPage = $request->input('rowsPerPage', 10);
        $name = $request->input('name') ?? "";
        $supplier = Supplier::when($name, function ($query) use ($name) {
            $query->where('name', 'like', '%' . $name . '%');
        })->paginate($rowsPerPage);
        return view('supplier.list', compact('supplier', 'rowsPerPage', 'name'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('supplier.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);
        Supplier::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'balance' => $request->balance,
        ]);
        return redirect()->route('supplier.index')->with('message', 'Record Create Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function status($id, $status)
    {
        $item = Supplier::find($id);
        $item->status = $status;
        $item->save();
        return redirect()->back()->with('message', 'Status Update Successfully');
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $supplier = Supplier::find($id);
        return view('supplier.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $supplier = Supplier::find($id);
        $supplier->name = $request->name;
        $supplier->phone = $request->phone;
        $supplier->address = $request->address;
        $supplier->balance = $request->balance;
        $supplier->save();
        return redirect()->route('supplier.index')->with('message', 'Record Update Successfully');
    }

    private function getSupplierLedger($supplier_id, $from_date, $to_date)
    {
        $from = $from_date ?? Carbon::now()->format('Y-m-d');
        $to   = $to_date ?? Carbon::now()->format('Y-m-d');

        // CACHE KEY
        $cacheKey = "supplier_ledger_{$supplier_id}_{$from}_{$to}";
        return Cache::remember($cacheKey, 60 * 60, function () use ($supplier_id, $from, $to) {
            $purchase = Purchase::select(
                'date',
                DB::raw("CONCAT('Purchase: ', COALESCE(note, ' '),
                ' <a href=\"http://localhost:8000/purchase/edit/', id, '\" target=\"_blank\" style=\"color:blue; text-decoration: underline;\">View Invoice</a>') as details"),
                DB::raw("0 as debit"),
                "net_price as credit"
            )
                ->where('supplier_id', $supplier_id)
                ->where('is_return', false)
                ->whereBetween('date', [$from, $to]);

            $purchaseReturn = Purchase::select(
                'date',
                DB::raw("CONCAT('Purchase Return: ', COALESCE(note, ' '),
                ' <a href=\"http://localhost:8000/purchase/edit/', id, '\" target=\"_blank\" style=\"color:blue; text-decoration: underline;\">View Invoice</a>') as details"),
                "net_price as debit",
                DB::raw("0 as credit")
            )
                ->where('supplier_id', $supplier_id)
                ->where('is_return', true)
                ->whereBetween('date', [$from, $to]);

            $payments = SupplierPayments::select(
                'date',
                DB::raw("CONCAT('Payment: ', COALESCE(details, ' ')) as details"),
                "payment as debit",
                DB::raw("0 as credit")
            )
                ->where('supplier_id', $supplier_id)
                ->whereBetween('date', [$from, $to]);

            return $purchase
                ->union($purchaseReturn)
                ->union($payments)
                ->orderBy('date', 'asc')
                ->get();
        });
    }

    public function ledger(Request $request, $id)
    {

        $dates = array_map('trim', explode(',', $request->input('dates'), 2));
        $from_date =  !empty($dates[0]) ? $dates[0] : Carbon::now()->format('Y-m-d');
        $to_date = !empty($dates[1]) ? $dates[1] : Carbon::now()->format('Y-m-d');
        $rowsPerPage = $request->input('rowsPerPage', 10);
        $sn = 1;
        $format_from_date = Carbon::parse($from_date)->format('Y-m-d');
        $format_to_date = Carbon::parse($to_date)->format('Y-m-d');
        $ledger = $this->getSupplierLedger($id, $format_from_date, $format_to_date);
        $supplier = Supplier::find($id);

        return view('supplier.ledger', compact('sn', 'supplier', 'ledger', 'format_from_date', 'format_to_date'));
    }

    public function downloadPDF($id, $dateFrom, $dateTo)
    {
        $supplier = Supplier::findOrFail($id);
        $ledger = $this->getSupplierLedger($id, $dateFrom, $dateTo);
        $pdf = \PDF::loadView('supplier.ledgerPDF',  compact('ledger', 'supplier'));
        return $pdf->download("ledger-{$supplier->name}.pdf");
    }

    public function destroy(string $id)
    {
        Supplier::find($id)->delete();
        return redirect()->back()->with('message', 'Record delete Successfully');
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
                Supplier::where('id', $multis)->delete();
            }
            return redirect()->back()->with('message', 'Selected Records delete Successfully');
        }
        if ($action == 'status_on') {
            foreach ($multi as $multis) {
                Supplier::where('id', $multis)->update(['status' => 1]);
            }
            return redirect()->back()->with('message', 'Selected Rocords Status ON Successfully');
        }
        if ($action == 'status_off') {
            foreach ($multi as $multis) {
                Supplier::where('id', $multis)->update(['status' => 0]);
            }
            return redirect()->back()->with('message', 'Selected Rocords Status OFF Successfully');
        }
        if ($action == '') {
            return redirect()->back();
        }
    }
}
