<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    public function index()
    {
        // Get all main menus
        $menus = DB::table('tbl_menus')->get();

        // Get all submenus
        $subMenus = DB::table('tbl_sub_menus')->get();

        // Pass both to the view
        return view('menus.index', compact('menus', 'subMenus'));
    }



    public function create()
    {
        $parents = DB::table('tbl_menus')->whereNull('parent_id')->get();
        return view('menus.create', compact('parents'));
    }

    public function store(Request $request)
    {
        DB::table('tbl_menus')->insert([
            'name' => $request->name,
            'route' => $request->route,
            'icon' => $request->icon,
            'parent_id' => $request->parent_id ?: null,
            'order' => $request->order ?? 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('menus.index')->with('success', 'Menu added successfully');
    }

    public function edit($id)
    {
        $menu = DB::table('tbl_menus')->find($id);
        $parents = DB::table('tbl_menus')->whereNull('parent_id')->where('id', '!=', $id)->get();

        return view('menus.edit', compact('menu', 'parents'));
    }

    public function update(Request $request, $id)
    {
        DB::table('tbl_menus')->where('id', $id)->update([
            'name' => $request->name,
            'route' => $request->route,
            'icon' => $request->icon,
            'parent_id' => $request->parent_id ?: null,
            'order' => $request->order ?? 0,
            'updated_at' => now(),
        ]);

        return redirect()->route('menus.index')->with('success', 'Menu updated successfully');
    }

    public function destroy($id)
    {
        DB::table('tbl_menus')->where('id', $id)->delete();
        return redirect()->route('menus.index')->with('success', 'Menu deleted successfully');
    }
}
