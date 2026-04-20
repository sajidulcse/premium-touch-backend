<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NavbarItem;

class NavbarController extends Controller
{
    // ✅ Show All Items
    public function index()
    {
        return response()->json(NavbarItem::orderBy('position')->get());
    }

    // ✅ Store New Item
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'slug' => 'required|unique:navbar_items',
            'url' => 'required'
        ]);

        $item = NavbarItem::create($request->all());

        return response()->json([
            'message' => 'Navbar Item Created',
            'data' => $item
        ]);
    }

    // ✅ Show Single Item
    public function show($id)
    {
        return response()->json(NavbarItem::findOrFail($id));
    }

    // ✅ Update Item
    public function update(Request $request, $id)
    {
        $item = NavbarItem::findOrFail($id);
        $item->update($request->all());

        return response()->json([
            'message' => 'Navbar Updated',
            'data' => $item
        ]);
    }

    // ✅ Delete Item
    public function destroy($id)
    {
        NavbarItem::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Navbar Item Deleted'
        ]);
    }
}
