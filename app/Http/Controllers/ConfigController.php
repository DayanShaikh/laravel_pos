<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConfigVariable;
use App\Models\ConfigType;
use Illuminate\Support\Facades\Storage;

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
        $config = ConfigVariable::where('config_type_id', $id)->get();
        foreach ($config as $configs) {
            $confg = ConfigVariable::find($configs->id);
            $confg->value = $request[$configs->type . "_" . $configs->id];
            if ($request->hasFile($configs->type . "_" . $configs->id)) {
                Storage::delete($confg->value);
                $confg->value = $request->file($configs->type . "_" . $configs->id)->storeAs('public/config/image', $request->file($configs->type . "_" . $configs->id)->getClientOriginalName());
            }
            $confg->save();
        }
        return redirect()->back()->with('message', 'Record Update Successfully');
    }
}
