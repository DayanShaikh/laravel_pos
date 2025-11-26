<?php

namespace App\Http\Controllers;

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
        $permission = Permissions::all();
        return view('roles.create', compact('permission'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);
        $role = Roles::create([
            'name' => $request->name,
            'guard_name' => 'web'
        ]);
        $role->syncPermissions($request->input('permission'));
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
        $role = Roles::find($id);
        $permission = Permissions::all();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')->all();
        return view('roles.edit', compact('role', 'permission', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
        ]);
        $role = Roles::find($id);
        if (!empty($role)) {
            $role->update($validatedData);
            $role->syncPermissions($request->input('permission'));
        }
        return redirect()->route('role.index')->with('message', 'Record updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //dd($id);
        Roles::find($id)->delete();
        // DB::table('role_has_permissions')->where('role_has_permissions.role_id', $id)->delete();
        return redirect()->back()->with('message', 'Record delete successfully');
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
