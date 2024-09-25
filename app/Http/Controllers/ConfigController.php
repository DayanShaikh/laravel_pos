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
        // return $request;

        $config = ConfigVariable::where('config_type_id', $id)->get();
        foreach ($config as $configs) {
            $key = $configs->type . "_" . $configs->id;
            
            if ($request->hasFile($key)) {
                $confg = ConfigVariable::find($configs->id);
                // Delete the old file if it exists
                if ($confg->value) {
                    Storage::delete($confg->value);
                }
                // Store the new file
                $confg->value = $request->file($key)->storeAs('public/config/image', $request->file($key)->getClientOriginalName());
                $confg->save();
            } else {
                $confg = ConfigVariable::find($configs->id);
                $confg->value = $request->input($key);
                $confg->save();
            }
        }
        return redirect()->back()->with('message', 'Record Update Successfully');
    }
}
