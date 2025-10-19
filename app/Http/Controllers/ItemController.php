<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\ItemCategory;

class ItemController extends Controller
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
            $item = Item::where('title', 'like', '%' . $title . '%')->paginate($rowsPerPage);
        } else {
            $item = Item::paginate($rowsPerPage);
        }
        return view('items.list', compact('item', 'rowsPerPage', 'sn', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $item_category = ItemCategory::all();
        return view('items.create', compact('item_category'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
        ]);
        Item::create([
            'item_category_id' => $request->item_category_id,
            'title' => $request->title,
            'unit_price' => $request->unit_price,
            'sale_price' => $request->sale_price,
            'quantity' => $request->quantity,
        ]);
        return redirect()->route('item.index')->with('message', 'Record Create Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function status($id, $status)
    {
        $item = Item::find($id);
        $item->status = $status;
        $item->save();
        return redirect()->back()->with('message', 'Status Update Successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $item_category = ItemCategory::all();
        $item = Item::find($id);
        return view('items.edit', compact('item', 'item_category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $item = Item::find($id);
        $item->item_category_id = $request->item_category_id;
        $item->title = $request->title;
        $item->unit_price = $request->unit_price;
        $item->sale_price = $request->sale_price;
        $item->quantity = $request->quantity;
        $item->save();
        return redirect()->route('item.index')->with('message', 'Record Update Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Item::find($id)->delete();
        return redirect()->back()->with('message', 'Record delete Successfully');
    }

    public function bulkAction(Request $request)
    {
        $action = $request->action;
        $multi = $request->input('multidelete', []);
        if (empty($multi)) {
            return redirect()->back()->with('error', 'No Records Selected');
        }
        if ($action == 'delete') {
            foreach ($multi as $multis) {
                Item::where('id', $multis)->delete();
            }
            return redirect()->back()->with('message', 'Selected Records delete Successfully');
        }
        if ($action == 'status_on') {
            foreach ($multi as $multis) {
                Item::where('id', $multis)->update(['status' => 1]);
            }
            return redirect()->back()->with('message', 'Selected Rocords Status ON Successfully');
        }
        if ($action == 'status_off') {
            foreach ($multi as $multis) {
                Item::where('id', $multis)->update(['status' => 0]);
            }
            return redirect()->back()->with('message', 'Selected Rocords Status OFF Successfully');
        }
        if ($action == '') {
            return redirect()->back();
        }
    }
}
