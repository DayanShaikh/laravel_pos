<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConfigVariable;
use App\Models\ConfigType;

class ConfigController extends Controller
{
    public function index()
    {
        $config_type = ConfigType::all();
        foreach ($config_type as $configs) {
            $config = ConfigVariable::where('config_type_id', $configs->id)->get();
        }
        return view('config.list', compact('config', 'config_type'));
    }

    public function store(Request $request, $id)
    {
        // return $request;
        $config = ConfigVariable::where('config_type_id', $id)->get();
        foreach ($config as $configs) {
            // return $configs;
            $confg = ConfigVariable::find($configs->id);
            $confg->value = $request[$configs->type."_".$configs->id];
            $confg->save();
        }
        return redirect()->back()->with('message', 'Record Update Successfully');
    }
}
