<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConfigType;

class ConfigTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sn = 1;
        $config = ConfigType::paginate(10);
        return view('config_type.list', compact('config', 'sn'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('config_type.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required'
        ]);
        ConfigType::create([
            'title' => $request->title,
            'sortorder' => $request->sortorder
        ]);
        return redirect()->route('config_type.index')->with('message', 'Record Create Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $config = ConfigType::find($id);
        return view('config_type.edit', compact('config'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $config = ConfigType::find($id);
        $config->title = $request->title;
        $config->sortorder = $request->sortorder;
        $config->save();
        return redirect()->route('config_type.index')->with('message', 'Record Update Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        ConfigType::find($id)->delete();
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
                ConfigType::where('id', $multis)->delete();
            }
            return redirect()->back()->with('message', 'Selected Records delete Successfully');
        }
        if ($action == '') {
            return redirect()->back()->with('error', 'No Records Selected');
        }
    }
}
