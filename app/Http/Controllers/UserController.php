<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Roles;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $rowsPerPage = $request->input('rowsPerPage', 10);
        $name = $request->input('name') ?? "";
        $users = User::when($name, function($query) use($name) {
            $query->where('name', 'like', '%' . $name . '%');
        })->paginate($rowsPerPage);
        return view('user.list', compact('users', 'rowsPerPage', 'name'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Roles::get();
        return view('user.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $user->userRoles()->sync($request->roles);
        return redirect()->route('user.index')->with('message', 'Record Create Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function status($id, $status)
    {
        $user = User::find($id);
        $user->status = $status;
        $user->save();
        return redirect()->back()->with('message', 'Status Update Successfully');
    }

    public function edit(string $id)
    {
        $user = User::with('userRoles')->find($id);
        $roles = Roles::get();
        return view('user.edit', compact('user', 'roles'));
    }

    public function update(Request $request, string $id)
    {
        $user = User::find($id);
        $validate = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
        ]);
        if (!empty($request->password)) {
            $validate['password'] = Hash::make($request->password);
        }
        $user->update($validate);
        $user->userRoles()->sync([$request->role]);
        return redirect()->route('user.index')->with('message', 'Record updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        User::find($id)->delete();
        return redirect()->back()->with('message', 'Record delete successfully');
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
                User::where('id', $multis)->delete();
            }
            return redirect()->back()->with('message', 'Selected Records delete Successfully');
        }
        if ($action == 'status_on') {
            foreach ($multi as $multis) {
                User::where('id', $multis)->update(['status' => 1]);
            }
            return redirect()->back()->with('message', 'Selected Rocords Status ON Successfully');
        }
        if ($action == 'status_off') {
            foreach ($multi as $multis) {
                User::where('id', $multis)->update(['status' => 0]);
            }
            return redirect()->back()->with('message', 'Selected Rocords Status OFF Successfully');
        }
        if ($action == '') {
            return redirect()->back();
        }
    }
}
