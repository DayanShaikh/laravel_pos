<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use App\Models\Roles;
use App\Models\Permissions;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\DB;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $rowsPerPage = $request->input('rowsPerPage', 10);
        $role = Roles::paginate($rowsPerPage);
        return view('roles.list', compact('role', 'rowsPerPage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $menus = Menu::get();
        return view('roles.create', compact('menus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' =>  ['required']
        ]);
        $output = collect();
        foreach ($request->permissions as $model => $actions) {
            $output->push((object)[
                "model" => $model,
                "permissions" => collect($actions)
            ]);
        }
        $role = Roles::create([
            'title' => $request->name,
        ]);

        $output->each(function ($rec) use ($role) {
            $rec->permissions->each(function ($per) use ($rec, $role) {
                Permissions::create([
                    'role_id' => $role->id,
                    'model' => $rec->model,
                    'action' => $per,
                ]);
            });
        });
        return redirect()->route('role.index')->with('message', 'Record Create Successfully');
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
        $menus = Menu::get();
        $role = Roles::with('permissions')->find($id);
        $rolePermissions = [];
        foreach ($role->permissions as $p) {
            $rolePermissions[$p->model][] = $p->action;
        }
        return view('roles.edit', compact('role', 'menus', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' =>  ['required']
        ]);
        $output = collect();
        foreach ($request->permissions as $model => $actions) {
            $output->push((object)[
                "model" => $model,
                "permissions" => collect($actions)
            ]);
        }
        $role = Roles::find($id);
        if (!empty($role)) {
            $role->update([
                'title' => $request->name,
            ]);
            Permissions::where('role_id', $role->id)->delete();
            $output->each(function ($rec) use ($role) {
                $rec->permissions->each(function ($per) use ($rec, $role) {
                    Permissions::create([
                        'role_id' => $role->id,
                        'model' => $rec->model,
                        'action' => $per,
                    ]);
                });
            });
        }
        return redirect()->route('role.index')->with('message', 'Record updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Roles::find($id);
        if ($role->permissions()->exists()) {
            $role->permissions()->delete();
        }
        $role->delete();
        return redirect()->back()->with('message', 'Record deleted successfully');
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
                Roles::where('id', $multis)->delete();
            }
            return redirect()->back()->with('message', 'Selected Records delete Successfully');
        }
    }
}
