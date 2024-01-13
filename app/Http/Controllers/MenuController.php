<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\menu;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $rowsPerPage = $request->input('rowsPerPage', 10);
        $menu = Menu::paginate($rowsPerPage);
        return view('menu.list', compact('menu', 'rowsPerPage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $menu = Menu::all();
        return view('menu.create', compact('menu'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'url' => 'required',
            'small_icon' => 'required',
            'icon' => 'required',
        ]);
        Menu::create([
            'title' => $request->title,
            'url' => $request->url,
            'parent_id' => $request->parent_id,
            'small_icon' => $request->small_icon,
            'icon' => $request->icon,
        ]);
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
