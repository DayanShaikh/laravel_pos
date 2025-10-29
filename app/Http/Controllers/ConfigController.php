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
            $key = $configs->type . "_" . $configs->id;
            if ($request->hasFile($key)) {
                $confg = ConfigVariable::find($configs->id);
                // Delete the old file if it exists
                if ($confg->value) {
                    Storage::delete($confg->value);
                }
                // Store the new file
                $file = $request->file($key);
                // return $file;
                if ($file && $file->isValid()) {
                    $filename = time() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('config/image', $filename, 'public');
                    $confg->value = $path;
                }
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
