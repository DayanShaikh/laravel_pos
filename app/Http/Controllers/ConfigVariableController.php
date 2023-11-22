<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConfigVariable;
use App\Models\ConfigType;

class ConfigVariableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sn = 1;
        $rowsPerPage = $request->input('rowsPerPage', 10);
        $config = ConfigVariable::with('ConfigType')->paginate($rowsPerPage);
        return view('config_variable.list', compact('config', 'sn', 'rowsPerPage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $ctype = ConfigType::all();
        return view('config_variable.create', compact('ctype'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $config_variable = $request->validate([
            'config_type_id' => ['required'],
            'title' => 'required',
            'type' => ['required'],
            'key' => 'required',
        ]);
        ConfigVariable::create([
            'config_type_id' => $request->config_type_id,
            'title' => $request->title,
            'notes' => $request->note,
            'type' => $request->type,
            'default_values' => $request->default_values,
            'key' => $request->key,
            'value' => $request->value,
            'sortorder' => $request->sortorder,
        ]);
        return redirect()->route('config_variable.index')->with('message', 'Record Create Successfully');
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
        $config = ConfigVariable::find($id);
        $ctype = ConfigType::all();
        return view('config_variable.edit', compact('config', 'ctype'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $config = ConfigVariable::find($id);
        $config->config_type_id = $request->config_type_id;
        $config->title = $request->title;
        $config->notes = $request->notes;
        $config->type = $request->type;
        $config->default_values = $request->default_values;
        $config->key = $request->key;
        $config->value = $request->value;
        $config->sortorder = $request->sortorder;
        $config->save();
        return redirect()->route('config_variable.index')->with('message', 'Record Update Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        ConfigVariable::find($id)->delete();
        return redirect()->back()->with('message', 'Record delete Successfully');
    }
    
    public function bulkAction(Request $request)
    {
        $action = $request->action;
        $multi = $request->input('multidelete', []);
        // dd($multi);
        if (empty($multi) || empty($action)) {
            return redirect()->back()->with('error', 'No Records Selected');
        }
        if ($action == 'delete') {
            foreach ($multi as $multis) {
                ConfigVariable::where('id', $multis)->delete();
            }
            return redirect()->back()->with('message', 'Selected Records delete Successfully');
        }
    }
}
